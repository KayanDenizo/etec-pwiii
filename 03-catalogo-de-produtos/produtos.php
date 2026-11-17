<?php
require 'includes/auth.php';
require 'classes/Produto.class.php';
$p = new Produto();
$produtos = $p->conecta() ? $p->buscarProdutos() : array();

$pagina = 'produtos';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<?php require 'includes/head.php'; ?>
	<title>Produtos Cadastrados | Loja Etim</title>
</head>

<body>
	<?php require 'includes/navbar.php'; ?>

	<header class="faixa">
		<div class="container">
			<h1>Produtos cadastrados</h1>
			<p>Clique em um produto para ver todas as imagens.</p>
		</div>
	</header>

	<main class="container conteudo">
		<?php require 'includes/mensagem.php'; ?>

		<?php if (count($produtos) == 0): ?>
			<div class="cartao p-4 text-center">
				<p class="mb-3 text-secondary">Nenhum produto cadastrado ainda.</p>
				<a href="produto_cadastrar.php" class="btn btn-loja"><i class="bi bi-plus-square"></i> Cadastrar produto</a>
			</div>
		<?php else: ?>
			<div class="row g-4">
				<?php foreach ($produtos as $produto): ?>
					<div class="col-sm-6 col-md-4 col-lg-3">
						<div class="cartao produto-card h-100 d-flex flex-column">
							<a href="exibir_produto.php?id=<?php echo $produto['id_produto']; ?>" class="text-decoration-none text-reset">
								<?php if (!empty($produto['capa'])): ?>
									<img src="imagens/<?php echo htmlspecialchars($produto['capa']); ?>" alt="<?php echo htmlspecialchars($produto['nome_produto']); ?>">
								<?php else: ?>
									<div class="sem-imagem"><i class="bi bi-image"></i></div>
								<?php endif; ?>
								<div class="p-3 pb-2">
									<h2 class="h6 mb-1"><?php echo htmlspecialchars($produto['nome_produto']); ?></h2>
									<div class="preco">R$ <?php echo number_format($produto['valor'], 2, ',', '.'); ?></div>
								</div>
							</a>
							<div class="px-3 pb-3 mt-auto d-flex gap-2">
								<a href="produto_atualizar.php?id=<?php echo $produto['id_produto']; ?>" class="btn btn-outline-primary btn-sm flex-fill">
									<i class="bi bi-pencil"></i> Editar
								</a>
								<a href="produto_deletar.php?id=<?php echo $produto['id_produto']; ?>" class="btn btn-outline-danger btn-sm flex-fill">
									<i class="bi bi-trash"></i> Excluir
								</a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</main>

	<?php require 'includes/rodape.php'; ?>
</body>

</html>
