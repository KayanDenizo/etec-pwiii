<?php
require 'includes/auth.php';
require 'classes/Usuario.class.php';

$u = new Usuario();
if (!$u->conecta()) {
    die("Não foi possível conectar ao banco de dados.");
}

//a exclusao so acontece via POST, depois da confirmacao
if (isset($_POST['id_usuario'])) {
    $id = (int) $_POST['id_usuario'];
    $alvo = $u->buscarUsuarioPorId($id);

    if (!$alvo) {
        definirMensagem('warning', "Usuário não encontrado.");
    } elseif ($id == $_SESSION['id_usuario']) {
        //impede apagar a conta que esta logada
        definirMensagem('danger', "Você não pode excluir o seu próprio usuário enquanto está logado.");
    } elseif ($u->apagarUsuario($id)) {
        definirMensagem('success', "Usuário \"" . $alvo['nome'] . "\" excluído com sucesso!");
    } else {
        definirMensagem('danger', "Erro ao excluir o usuário.");
    }

    header("Location: usuarios.php");
    exit;
}

//sem id na URL: mostra a lista para escolher qual usuario apagar
$usuario = false;
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    $usuario = $u->buscarUsuarioPorId($id);
    if (!$usuario) {
        definirMensagem('warning', "Usuário não encontrado.");
        header("Location: usuario_deletar.php");
        exit;
    }
}

$usuarios = $usuario ? array() : $u->listarUsuarios();
$pagina = 'usuario_deletar';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php require 'includes/head.php'; ?>
    <title>Deletar Usuário | Loja Etim</title>
</head>

<body>
    <?php require 'includes/navbar.php'; ?>

    <header class="faixa">
        <div class="container">
            <h1>Deletar usuário</h1>
            <p>Remova o acesso de um usuário ao sistema.</p>
        </div>
    </header>

    <main class="container conteudo">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <?php require 'includes/mensagem.php'; ?>

                <div class="cartao p-4">
                    <?php if (!$usuario): ?>
                        <!-- Passo 1: escolher o usuario -->
                        <form method="get">
                            <label for="id" class="form-label">Escolha o usuário</label>
                            <select name="id" id="id" class="form-select mb-3" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($usuarios as $item): ?>
                                    <?php if ($item['id_usuario'] == $_SESSION['id_usuario']) continue; ?>
                                    <option value="<?php echo $item['id_usuario']; ?>">
                                        <?php echo htmlspecialchars($item['nome'] . ' (' . $item['email'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text mb-3">O seu próprio usuário não aparece na lista.</div>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-arrow-right"></i> Continuar
                            </button>
                        </form>
                    <?php elseif ($usuario['id_usuario'] == $_SESSION['id_usuario']): ?>
                        <div class="alert alert-warning mb-3">
                            Você não pode excluir o seu próprio usuário enquanto está logado.
                        </div>
                        <a href="usuarios.php" class="btn btn-outline-secondary">Voltar</a>
                    <?php else: ?>
                        <!-- Passo 2: confirmar a exclusao -->
                        <h2 class="h5">Tem certeza que deseja excluir este usuário?</h2>
                        <ul class="list-unstyled my-3">
                            <li><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></li>
                            <li><strong>E-mail:</strong> <?php echo htmlspecialchars($usuario['email']); ?></li>
                        </ul>
                        <p class="text-secondary">Essa ação não pode ser desfeita.</p>

                        <form method="post" class="d-flex gap-2">
                            <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Sim, excluir
                            </button>
                            <a href="usuarios.php" class="btn btn-outline-secondary">Cancelar</a>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php require 'includes/rodape.php'; ?>
</body>

</html>
