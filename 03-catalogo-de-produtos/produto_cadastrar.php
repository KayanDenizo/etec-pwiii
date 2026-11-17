<?php
require 'includes/auth.php';
require 'includes/upload.php';

$erros = array();

//checa se o usuario enviou o formulario
if (isset($_POST['nome'])) {
    //o prepare/bindValue da classe Produto ja protege contra SQL injection
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['desc']);
    $valor = trim($_POST['valor']);

    //move as fotos para a pasta imagens/ e guarda os nomes novos
    $fotos = salvarFotos($erros);

    //Verifica se todos os campos foram digitados no formulario
    if (!empty($nome) && !empty($descricao) && is_numeric($valor) && !empty($fotos)) {
        require 'classes/Produto.class.php';
        $p = new Produto();

        if ($p->conecta() && $p->enviarProduto($nome, $descricao, $valor, $fotos)) {
            if (count($erros) > 0) {
                definirMensagem('warning', "Produto cadastrado, mas: " . implode(' ', $erros));
            } else {
                definirMensagem('success', "Produto \"$nome\" cadastrado com " . count($fotos) . " imagem(ns)!");
            }
            header("Location: produtos.php");
            exit;
        }
        $erros[] = "Erro ao gravar o produto no banco de dados.";
    } else {
        $erros[] = "Preencha nome, descrição, valor e envie ao menos uma imagem JPG ou PNG.";
    }
}

$pagina = 'produto_cadastrar';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php require 'includes/head.php'; ?>
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
    <title>Cadastro de Produto | Loja Etim</title>
</head>

<body>
    <?php require 'includes/navbar.php'; ?>

    <section class="hero">
        <h1>Envio de Imagens</h1>
        <p>Cadastre um novo produto com nome, descrição, valor e fotos.</p>
    </section>

    <main>
        <form method="post" enctype="multipart/form-data" class="card-form">
            <?php if (count($erros) > 0): ?>
                <div class="alert alert-danger">
                    <?php foreach ($erros as $erro): ?>
                        <div><?php echo htmlspecialchars($erro); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <label for="nome">Nome do Produto</label>
            <input type="text" name="nome" id="nome" placeholder="Ex: Sofá Confort 3 Lugares" maxlength="100" required>

            <label for="desc">Descrição</label>
            <textarea name="desc" id="desc" placeholder="Descreva as principais características do produto" required></textarea>

            <label for="val">Valor</label>
            <input type="number" name="valor" id="val" step="0.01" min="0" placeholder="0,00" required>

            <label for="foto">Imagens do Produto</label>
            <div class="upload-wrap">
                <input type="file" name="foto[]" multiple id="foto" class="upload-input" accept="image/png, image/jpeg">
                <div class="upload-box" id="upload-box">
                    <span class="icone">🖼️</span>
                    <strong>Clique ou arraste as imagens aqui</strong>
                    <small id="arquivos-nome">JPG ou PNG &middot; pode selecionar várias</small>
                </div>
            </div>

            <button type="submit" id="botao">Enviar Produto</button>
        </form>
    </main>

    <?php require 'includes/rodape.php'; ?>

    <script>
        const inputFoto = document.getElementById('foto');
        const caixaUpload = document.getElementById('upload-box');
        const nomeArquivos = document.getElementById('arquivos-nome');

        inputFoto.addEventListener('change', () => {
            if (inputFoto.files.length === 0) {
                nomeArquivos.textContent = 'JPG ou PNG · pode selecionar várias';
            } else if (inputFoto.files.length === 1) {
                nomeArquivos.textContent = inputFoto.files[0].name;
            } else {
                nomeArquivos.textContent = inputFoto.files.length + ' arquivos selecionados';
            }
        });

        ['dragenter', 'dragover'].forEach(evento =>
            inputFoto.addEventListener(evento, () => caixaUpload.classList.add('arrastando'))
        );
        ['dragleave', 'drop'].forEach(evento =>
            inputFoto.addEventListener(evento, () => caixaUpload.classList.remove('arrastando'))
        );
    </script>
</body>

</html>
