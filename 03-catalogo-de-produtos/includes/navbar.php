<?php
//NavBar do Bootstrap usada em todas as paginas internas.
//Antes de incluir, a pagina define $pagina para marcar o menu ativo.
if (!isset($pagina)) {
    $pagina = '';
}
$menuProdutos = in_array($pagina, array('produtos', 'produto_cadastrar', 'produto_atualizar', 'produto_deletar', 'catalogo'));
$menuUsuarios = in_array($pagina, array('usuarios', 'usuario_inserir', 'usuario_atualizar', 'usuario_deletar'));
?>
<nav class="navbar navbar-expand-lg navbar-loja sticky-top">
    <div class="container">
        <a class="navbar-brand" href="principal.php">Loja Etim</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal"
            aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo $pagina == 'principal' ? 'active' : ''; ?>" href="principal.php">
                        <i class="bi bi-house-door"></i> Início
                    </a>
                </li>

                <!-- Menu de Produtos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo $menuProdutos ? 'active' : ''; ?>" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-box-seam"></i> Produtos
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item <?php echo $pagina == 'produtos' ? 'active' : ''; ?>" href="produtos.php">
                                <i class="bi bi-grid"></i> Produtos cadastrados
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php echo $pagina == 'produto_cadastrar' ? 'active' : ''; ?>" href="produto_cadastrar.php">
                                <i class="bi bi-plus-square"></i> Cadastrar produto com imagens
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php echo $pagina == 'produto_atualizar' ? 'active' : ''; ?>" href="produto_atualizar.php">
                                <i class="bi bi-pencil-square"></i> Atualizar produto
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php echo $pagina == 'produto_deletar' ? 'active' : ''; ?>" href="produto_deletar.php">
                                <i class="bi bi-trash"></i> Deletar produto
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item <?php echo $pagina == 'catalogo' ? 'active' : ''; ?>" href="produtosestatico.php">
                                <i class="bi bi-shop"></i> Catálogo estático
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Menu de Usuarios -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo $menuUsuarios ? 'active' : ''; ?>" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-people"></i> Usuários
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item <?php echo $pagina == 'usuarios' ? 'active' : ''; ?>" href="usuarios.php">
                                <i class="bi bi-list-ul"></i> Listar usuários
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php echo $pagina == 'usuario_inserir' ? 'active' : ''; ?>" href="usuario_inserir.php">
                                <i class="bi bi-person-plus"></i> Inserir usuário
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php echo $pagina == 'usuario_atualizar' ? 'active' : ''; ?>" href="usuario_atualizar.php">
                                <i class="bi bi-pencil-square"></i> Atualizar usuário
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php echo $pagina == 'usuario_deletar' ? 'active' : ''; ?>" href="usuario_deletar.php">
                                <i class="bi bi-person-x"></i> Deletar usuário
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <span class="navbar-text usuario-logado">
                    <i class="bi bi-person-circle"></i>
                    <?php echo htmlspecialchars($_SESSION['nome']); ?>
                </span>
                <a href="logout.php" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Sair
                </a>
            </div>
        </div>
    </div>
</nav>
