<?php
require 'includes/auth.php';
require 'classes/Produto.class.php';
$p = new Produto();
$produto = false;
if (isset($_GET['id']) && $p->conecta()) {
	$produto = $p->buscarProdutosPorId((int) $_GET['id']);
}

//produto inexistente: volta para a lista
if (empty($produto['id_produto'])) {
	definirMensagem('warning', "Produto não encontrado.");
	header("Location: produtos.php");
	exit;
}

$pagina = 'produtos';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<?php require 'includes/head.php'; ?>
	<title><?php echo htmlspecialchars($produto['nome_produto']); ?> | Loja Etim</title>
</head>

<body>
	<?php require 'includes/navbar.php'; ?>

	<header class="faixa">
		<div class="container">
			<a href="produtos.php" class="link-light text-decoration-none small"><i class="bi bi-arrow-left"></i> Produtos cadastrados</a>
			<h1 class="mt-2"><?php echo htmlspecialchars($produto['nome_produto']); ?></h1>
		</div>
	</header>

	<main class="container conteudo">
		<?php require 'includes/mensagem.php'; ?>

		<div class="cartao p-4">
			<div class="row g-4">
				<div class="col-lg-7">
					<?php if (count($produto['imagens']) > 0): ?>
						<div id="galeria" class="carousel slide galeria rounded-4 overflow-hidden">
							<div class="carousel-inner">
								<?php foreach ($produto['imagens'] as $i => $imagem): ?>
									<div class="carousel-item <?php echo $i == 0 ? 'active' : ''; ?>">
										<img src="imagens/<?php echo htmlspecialchars($imagem); ?>" alt="Imagem <?php echo $i + 1; ?>">
									</div>
								<?php endforeach; ?>
							</div>
							<?php if (count($produto['imagens']) > 1): ?>
								<button class="carousel-control-prev" type="button" data-bs-target="#galeria" data-bs-slide="prev">
									<span class="carousel-control-prev-icon" aria-hidden="true"></span>
									<span class="visually-hidden">Anterior</span>
								</button>
								<button class="carousel-control-next" type="button" data-bs-target="#galeria" data-bs-slide="next">
									<span class="carousel-control-next-icon" aria-hidden="true"></span>
									<span class="visually-hidden">Próxima</span>
								</button>
							<?php endif; ?>
						</div>

						<?php if (count($produto['imagens']) > 1): ?>
							<div class="miniaturas d-flex flex-wrap gap-2 mt-3">
								<?php foreach ($produto['imagens'] as $i => $imagem): ?>
									<img src="imagens/<?php echo htmlspecialchars($imagem); ?>" alt="Miniatura <?php echo $i + 1; ?>"
										data-bs-target="#galeria" data-bs-slide-to="<?php echo $i; ?>">
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					<?php else: ?>
						<div class="sem-imagem rounded-4" style="height: 380px;"><i class="bi bi-image"></i></div>
					<?php endif; ?>
				</div>

				<div class="col-lg-5">
					<div class="preco fs-3 mb-3">R$ <?php echo number_format($produto['valor'], 2, ',', '.'); ?></div>
					<h2 class="h6 text-uppercase text-secondary">Descrição</h2>
					<p style="white-space: pre-line;"><?php echo htmlspecialchars($produto['descricao']); ?></p>
					<p class="text-secondary small"><?php echo count($produto['imagens']); ?> imagem(ns) cadastrada(s)</p>
					<div class="d-flex gap-2">
						<a href="produto_atualizar.php?id=<?php echo $produto['id_produto']; ?>" class="btn btn-outline-primary">
							<i class="bi bi-pencil"></i> Editar
						</a>
						<a href="produto_deletar.php?id=<?php echo $produto['id_produto']; ?>" class="btn btn-outline-danger">
							<i class="bi bi-trash"></i> Excluir
						</a>
					</div>
				</div>
			</div>
		</div>
	</main>

	<?php require 'includes/rodape.php'; ?>
</body>

</html>
