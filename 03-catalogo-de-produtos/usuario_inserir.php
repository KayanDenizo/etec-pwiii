<?php
require 'includes/auth.php';
require 'classes/Usuario.class.php';

$erros = array();
$nome = '';
$email = '';

if (isset($_POST['nome'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmar'];

    //validacoes dos campos
    if (empty($nome)) {
        $erros[] = "Informe o nome.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Informe um e-mail válido.";
    }
    if (strlen($senha) < 6) {
        $erros[] = "A senha deve ter pelo menos 6 caracteres.";
    }
    if ($senha !== $confirmar) {
        $erros[] = "As senhas não conferem.";
    }

    if (count($erros) == 0) {
        $u = new Usuario();
        if (!$u->conecta()) {
            $erros[] = "Não foi possível conectar ao banco de dados.";
        } elseif ($u->emailCadastrado($email)) {
            $erros[] = "Este e-mail já está cadastrado.";
        } elseif ($u->inserirUsuario($nome, $email, $senha)) {
            definirMensagem('success', "Usuário \"$nome\" inserido com sucesso!");
            header("Location: usuarios.php");
            exit;
        } else {
            $erros[] = "Erro ao inserir o usuário.";
        }
    }
}

$pagina = 'usuario_inserir';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php require 'includes/head.php'; ?>
    <title>Inserir Usuário | Loja Etim</title>
</head>

<body>
    <?php require 'includes/navbar.php'; ?>

    <header class="faixa">
        <div class="container">
            <h1>Inserir usuário</h1>
            <p>Cadastre um novo acesso ao sistema.</p>
        </div>
    </header>

    <main class="container conteudo">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="cartao p-4">
                    <?php if (count($erros) > 0): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($erros as $erro): ?>
                                <div><?php echo htmlspecialchars($erro); ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" maxlength="100"
                                value="<?php echo htmlspecialchars($nome); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" name="email" id="email" maxlength="150"
                                value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" name="senha" id="senha" minlength="6" required>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="confirmar" class="form-label">Confirmar senha</label>
                                <input type="password" class="form-control" name="confirmar" id="confirmar" minlength="6" required>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <button type="submit" class="btn btn-loja">
                                <i class="bi bi-check-lg"></i> Inserir
                            </button>
                            <a href="usuarios.php" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php require 'includes/rodape.php'; ?>
</body>

</html>
