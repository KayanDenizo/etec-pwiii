<?php
require 'includes/auth.php';
require 'classes/Usuario.class.php';

$u = new Usuario();
$usuarios = $u->conecta() ? $u->listarUsuarios() : array();

$pagina = 'usuarios';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php require 'includes/head.php'; ?>
    <title>Usuários | Loja Etim</title>
</head>

<body>
    <?php require 'includes/navbar.php'; ?>

    <header class="faixa">
        <div class="container">
            <h1>Usuários</h1>
            <p>Todos os usuários com acesso ao sistema.</p>
        </div>
    </header>

    <main class="container conteudo">
        <?php require 'includes/mensagem.php'; ?>

        <div class="cartao p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h2 class="h5 mb-0"><?php echo count($usuarios); ?> usuário(s) cadastrado(s)</h2>
                <a href="usuario_inserir.php" class="btn btn-loja btn-sm">
                    <i class="bi bi-person-plus"></i> Inserir usuário
                </a>
            </div>

            <?php if (count($usuarios) == 0): ?>
                <p class="text-secondary mb-0">Nenhum usuário cadastrado.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo $usuario['id_usuario']; ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($usuario['nome']); ?>
                                        <?php if ($usuario['id_usuario'] == $_SESSION['id_usuario']): ?>
                                            <span class="badge text-bg-success ms-1">você</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td class="text-end text-nowrap">
                                        <a href="usuario_atualizar.php?id=<?php echo $usuario['id_usuario']; ?>"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <a href="usuario_deletar.php?id=<?php echo $usuario['id_usuario']; ?>"
                                            class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i> Excluir
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php require 'includes/rodape.php'; ?>
</body>

</html>
