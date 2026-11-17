<?php
require 'includes/auth.php';
require 'classes/Usuario.class.php';

$u = new Usuario();
if (!$u->conecta()) {
    die("Não foi possível conectar ao banco de dados.");
}

$erros = array();
$usuario = false;

//sem id na URL: mostra a lista para escolher qual usuario atualizar
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    $usuario = $u->buscarUsuarioPorId($id);
    if (!$usuario) {
        definirMensagem('warning', "Usuário não encontrado.");
        header("Location: usuario_atualizar.php");
        exit;
    }
}

if ($usuario && isset($_POST['nome'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmar'];

    //validacoes (a senha e opcional: em branco mantem a atual)
    if (empty($nome)) {
        $erros[] = "Informe o nome.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Informe um e-mail válido.";
    }
    if (!empty($senha) && strlen($senha) < 6) {
        $erros[] = "A nova senha deve ter pelo menos 6 caracteres.";
    }
    if ($senha !== $confirmar) {
        $erros[] = "As senhas não conferem.";
    }
    if (count($erros) == 0 && $u->emailCadastrado($email, $id)) {
        $erros[] = "Este e-mail já pertence a outro usuário.";
    }

    if (count($erros) == 0) {
        if ($u->alterarUsuario($id, $nome, $email, $senha)) {
            //se alterou o proprio nome, atualiza o nome exibido na NavBar
            if ($id == $_SESSION['id_usuario']) {
                $_SESSION['nome'] = $nome;
            }
            definirMensagem('success', "Usuário \"$nome\" atualizado com sucesso!");
            header("Location: usuarios.php");
            exit;
        }
        $erros[] = "Erro ao atualizar o usuário.";
    }

    //mantem no formulario o que foi digitado
    $usuario['nome'] = $nome;
    $usuario['email'] = $email;
}

$usuarios = $usuario ? array() : $u->listarUsuarios();
$pagina = 'usuario_atualizar';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php require 'includes/head.php'; ?>
    <title>Atualizar Usuário | Loja Etim</title>
</head>

<body>
    <?php require 'includes/navbar.php'; ?>

    <header class="faixa">
        <div class="container">
            <h1>Atualizar usuário</h1>
            <p>Altere o nome, o e-mail ou a senha de um usuário.</p>
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
                                    <option value="<?php echo $item['id_usuario']; ?>">
                                        <?php echo htmlspecialchars($item['nome'] . ' (' . $item['email'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-loja">
                                <i class="bi bi-arrow-right"></i> Continuar
                            </button>
                        </form>
                    <?php else: ?>
                        <!-- Passo 2: editar os dados -->
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
                                    value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" name="email" id="email" maxlength="150"
                                    value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label for="senha" class="form-label">Nova senha</label>
                                    <input type="password" class="form-control" name="senha" id="senha" minlength="6">
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label for="confirmar" class="form-label">Confirmar nova senha</label>
                                    <input type="password" class="form-control" name="confirmar" id="confirmar" minlength="6">
                                </div>
                            </div>
                            <div class="form-text mb-3">Deixe a senha em branco para manter a atual.</div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-loja">
                                    <i class="bi bi-check-lg"></i> Salvar alterações
                                </button>
                                <a href="usuarios.php" class="btn btn-outline-secondary">Cancelar</a>
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
