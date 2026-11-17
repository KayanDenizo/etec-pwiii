<?php
session_start();

//se ja estiver logado, vai direto para a pagina principal
if (isset($_SESSION['id_usuario'])) {
    header("Location: principal.php");
    exit;
}

$erro = '';
$email = '';

if (isset($_POST['email']) && isset($_POST['senha'])) {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        $erro = "Informe o e-mail e a senha.";
    } else {
        require 'classes/Usuario.class.php';
        $u = new Usuario();

        if (!$u->conecta()) {
            $erro = "Não foi possível conectar ao banco de dados.";
        } else {
            $usuario = $u->logar($email, $senha);

            if ($usuario) {
                //login certo: guarda o usuario na sessao e vai para a pagina principal
                session_regenerate_id(true);
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['nome'] = $usuario['nome'];
                header("Location: principal.php");
                exit;
            }

            $erro = "E-mail ou senha incorretos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php require 'includes/head.php'; ?>
    <title>Login | Loja Etim</title>
</head>

<body class="pagina-login">
    <main class="cartao login-card">
        <div class="text-center mb-4">
            <div class="marca">Loja Etim</div>
            <p class="text-secondary mb-0">Entre para gerenciar produtos e usuários</p>
        </div>

        <?php if ($erro): ?>
            <div class="alert alert-danger py-2" role="alert">
                <i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" name="email" id="email"
                    value="<?php echo htmlspecialchars($email); ?>" placeholder="voce@email.com" required autofocus>
            </div>
            <div class="mb-4">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" name="senha" id="senha" placeholder="Sua senha" required>
            </div>
            <button type="submit" class="btn btn-loja w-100 py-2">
                <i class="bi bi-box-arrow-in-right"></i> Entrar
            </button>
        </form>
    </main>
</body>

</html>
