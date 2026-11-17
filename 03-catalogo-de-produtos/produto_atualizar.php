<?php
require 'includes/auth.php';
require 'includes/upload.php';
require 'classes/Produto.class.php';

$p = new Produto();
if (!$p->conecta()) {
    die("Não foi possível conectar ao banco de dados.");
}

$erros = array();
$produto = false;

//sem id na URL: mostra a lista para escolher qual produto atualizar
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    $produto = $p->buscarProdutosPorId($id);
    if (empty($produto['id_produto'])) {
        definirMensagem('warning', "Produto não encontrado.");
        header("Location: produto_atualizar.php");
        exit;
    }
}

if ($produto && isset($_POST['nome'])) {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['desc']);
    $valor = trim($_POST['valor']);

    //imagens marcadas para remover (so aceita imagens deste produto)
    $remover = array();
    if (isset($_POST['remover']) && is_array($_POST['remover'])) {
        $remover = array_values(array_unique(array_intersect($_POST['remover'], $produto['imagens'])));
    }

    if (empty($nome) || empty($descricao) || !is_numeric($valor)) {
        $erros[] = "Preencha nome, descrição e valor.";
    } else {
        //move as fotos novas para a pasta imagens/ e guarda os nomes
        $fotos = salvarFotos($erros);

        //o produto precisa continuar com pelo menos uma imagem
        if (count($produto['imagens']) - count($remover) + count($fotos) == 0) {
            $erros[] = "O produto precisa ficar com pelo menos uma imagem.";
            foreach ($fotos as $foto) {
                apagarArquivoImagem($foto);
            }
        } elseif ($p->alterarProduto($id, $nome, $descricao, $valor, $fotos)) {
            //apaga do banco e da pasta as imagens marcadas
            foreach ($remover as $imagem) {
                $p->apagarImagem($id, $imagem);
                apagarArquivoImagem($imagem);
            }

            if (count($erros) > 0) {
                definirMensagem('warning', "Produto atualizado, mas: " . implode(' ', $erros));
            } else {
                definirMensagem('success', "Produto \"$nome\" atualizado com sucesso!");
            }
            header("Location: exibir_produto.php?id=" . $id);
            exit;
        } else {
            $erros[] = "Erro ao atualizar o produto.";
        }
    }

    //mantem no formulario o que foi digitado
    $produto['nome_produto'] = $nome;
    $produto['descricao'] = $descricao;
    $produto['valor'] = $valor;
}

$produtos = $produto ? array() : $p->buscarProdutos();
$pagina = 'produto_atualizar';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php require 'includes/head.php'; ?>
    <title>Atualizar Produto | Loja Etim</title>
</head>

<body>
    <?php require 'includes/navbar.php'; ?>

    <header class="faixa">
        <div class="container">
            <h1>Atualizar produto</h1>
            <p>Altere os dados, remova imagens ou envie imagens novas.</p>
        </div>
    </header>

    <main class="container conteudo">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php require 'includes/mensagem.php'; ?>

                <div class="cartao p-4">
                    <?php if (!$produto): ?>
                        <!-- Passo 1: escolher o produto -->
                        <?php if (count($produtos) == 0): ?>
                            <p class="text-secondary mb-0">Nenhum produto cadastrado.</p>
                        <?php else: ?>
                            <form method="get">
                                <label for="id" class="form-label">Escolha o produto</label>
                                <select name="id" id="id" class="form-select mb-3" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($produtos as $item): ?>
                                        <option value="<?php echo $item['id_produto']; ?>">
                                            <?php echo htmlspecialchars($item['nome_produto']); ?>
                                            (R$ <?php echo number_format($item['valor'], 2, ',', '.'); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-loja">
                                    <i class="bi bi-arrow-right"></i> Continuar
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Passo 2: editar os dados -->
                        <?php if (count($erros) > 0): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($erros as $erro): ?>
                                    <div><?php echo htmlspecialchars($erro); ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome do produto</label>
                                <input type="text" class="form-control" name="nome" id="nome" maxlength="100"
                                    value="<?php echo htmlspecialchars($produto['nome_produto']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="desc" class="form-label">Descrição</label>
                                <textarea class="form-control" name="desc" id="desc" rows="4" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="val" class="form-label">Valor (R$)</label>
                                <input type="number" class="form-control" name="valor" id="val" step="0.01" min="0"
                                    value="<?php echo htmlspecialchars($produto['valor']); ?>" required>
                            </div>

                            <label class="form-label">Imagens atuais</label>
                            <?php if (count($produto['imagens']) == 0): ?>
                                <p class="text-secondary small">Este produto não tem imagens.</p>
                            <?php else: ?>
                                <div class="row g-3 mb-1">
                                    <?php foreach ($produto['imagens'] as $i => $imagem): ?>
                                        <div class="col-6 col-sm-4 col-md-3">
                                            <div class="imagem-atual">
                                                <img src="imagens/<?php echo htmlspecialchars($imagem); ?>" alt="Imagem <?php echo $i + 1; ?>">
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" name="remover[]"
                                                        value="<?php echo htmlspecialchars($imagem); ?>" id="remover<?php echo $i; ?>">
                                                    <label class="form-check-label small" for="remover<?php echo $i; ?>">Remover</label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3 mt-3">
                                <label for="foto" class="form-label">Adicionar imagens</label>
                                <input type="file" class="form-control" name="foto[]" id="foto" multiple accept="image/png, image/jpeg">
                                <div class="form-text">JPG ou PNG. As novas imagens são somadas às que ficarem.</div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-loja">
                                    <i class="bi bi-check-lg"></i> Salvar alterações
                                </button>
                                <a href="exibir_produto.php?id=<?php echo $produto['id_produto']; ?>" class="btn btn-outline-secondary">Cancelar</a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php require 'includes/rodape.php'; ?>
</body>

</html>
