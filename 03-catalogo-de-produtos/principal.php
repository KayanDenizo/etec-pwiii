<?php
require 'includes/auth.php';
require 'classes/Produto.class.php';
require 'classes/Usuario.class.php';

//contadores mostrados nos cartoes da pagina principal
$p = new Produto();
$u = new Usuario();
$totalProdutos = $p->conecta() ? count($p->buscarProdutos()) : 0;
$totalUsuarios = $u->conecta() ? count($u->listarUsuarios()) : 0;

$pagina = 'principal';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php require 'includes/head.php'; ?>
    <title>Página Principal | Loja Etim</title>
</head>

<body>
    <?php require 'includes/navbar.php'; ?>

    <header class="faixa">
        <div class="container">
            <h1>Olá, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</h1>
            <p>Use o menu acima ou os atalhos abaixo para gerenciar a loja.</p>
        </div>
    </header>

    <main class="container conteudo">
        <?php require 'includes/mensagem.php'; ?>

        <div class="row g-4">
            <div class="col-sm-6 col-lg-3">
                <a href="produtos.php" class="cartao cartao-atalho">
                    <span class="icone"><i class="bi bi-grid"></i></span>
                    <div class="numero"><?php echo $totalProdutos; ?></div>
                    <h2>Produtos cadastrados</h2>
                    <p>Veja os produtos e suas imagens.</p>
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <a href="produto_cadastrar.php" class="cartao cartao-atalho">
                    <span class="icone"><i class="bi bi-plus-square"></i></span>
                    <h2>Cadastrar produto</h2>
                    <p>Adicione um produto com nome, descrição, valor e fotos.</p>
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <a href="usuarios.php" class="cartao cartao-atalho">
                    <span class="icone"><i class="bi bi-people"></i></span>
                    <div class="numero"><?php echo $totalUsuarios; ?></div>
                    <h2>Usuários</h2>
                    <p>Liste, atualize ou exclua usuários.</p>
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <a href="usuario_inserir.php" class="cartao cartao-atalho">
                    <span class="icone"><i class="bi bi-person-plus"></i></span>
                    <h2>Inserir usuário</h2>
                    <p>Crie um novo acesso ao sistema.</p>
                </a>
            </div>
        </div>
    </main>

    <?php require 'includes/rodape.php'; ?>
</body>

</html>
