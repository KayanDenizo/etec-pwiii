<?php
//exibe (uma unica vez) a mensagem guardada por definirMensagem()
if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']);
?>
    <div class="alert alert-<?php echo $mensagem['tipo']; ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($mensagem['texto']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
<?php
}
?>
