<?php
require 'includes/auth.php';
require 'includes/upload.php';
require 'classes/Produto.class.php';

$p = new Produto();
if (!$p->conecta()) {
    die("Não foi possível conectar ao banco de dados.");
}

//a exclusao so acontece via POST, depois da confirmacao
if (isset($_POST['id_produto'])) {
    $id = (int) $_POST['id_produto'];
    $alvo = $p->buscarProdutosPorId($id);

    if (empty($alvo['id_produto'])) {
        definirMensagem('warning', "Produto não encontrado.");
    } elseif ($p->apagarProduto($id)) {
        //apaga tambem os arquivos das imagens da pasta imagens/
        foreach ($alvo['imagens'] as $imagem) {
            apagarArquivoImagem($imagem);
        }
        definirMensagem('success', "Produto \"" . $alvo['nome_produto'] . "\" excluído com sucesso!");
    } else {
        definirMensagem('danger', "Erro ao excluir o produto.");
    }

    header("Location: produtos.php");
    exit;
}

//sem id na URL: mostra a lista para escolher qual produto apagar
$produto = false;
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    $produto = $p->buscarProdutosPorId($id);
    if (empty($produto['id_produto'])) {
        definirMensagem('warning', "Produto não encontrado.");
        header("Location: produto_deletar.php");
        exit;
    }
}

$produtos = $produto ? array() : $p->buscarProdutos();
$pagina = 'produto_deletar';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php require 'includes/head.php'; ?>
    <title>Deletar Produto | Loja Etim</title>
</head>

<body>
    <?php require 'includes/navbar.php'; ?>

    <header class="faixa">
        <div class="container">
            <h1>Deletar produto</h1>
            <p>Remova um produto e todas as imagens dele.</p>
        </div>
    </header>

    <main class="container conteudo">
        <div class="row justify-content-center">
            <div class="col-lg-6">
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
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-arrow-right"></i> Continuar
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Passo 2: confirmar a exclusao -->
                        <h2 class="h5">Tem certeza que deseja excluir este produto?</h2>
                        <ul class="list-unstyled my-3">
                            <li><strong>Nome:</strong> <?php echo htmlspecialchars($produto['nome_produto']); ?></li>
                            <li><strong>Valor:</strong> R$ <?php echo number_format($produto['valor'], 2, ',', '.'); ?></li>
                            <li><strong>Imagens:</strong> <?php echo count($produto['imagens']); ?></li>
                        </ul>
                        <?php if (count($produto['imagens']) > 0): ?>
                            <div class="miniaturas d-flex flex-wrap gap-2 mb-3">
                                <?php foreach ($produto['imagens'] as $imagem): ?>
                                    <img src="imagens/<?php echo htmlspecialchars($imagem); ?>" alt="">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <p class="text-secondary">O produto e as imagens serão apagados. Essa ação não pode ser desfeita.</p>

                        <form method="post" class="d-flex gap-2">
                            <input type="hidden" name="id_produto" value="<?php echo $produto['id_produto']; ?>">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Sim, excluir
                            </button>
                            <a href="produtos.php" class="btn btn-outline-secondary">Cancelar</a>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php require 'includes/rodape.php'; ?>
</body>

</html>
