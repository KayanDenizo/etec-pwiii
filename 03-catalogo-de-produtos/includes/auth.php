<?php
//protege as paginas internas: so entra quem fez login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

//guarda uma mensagem para ser exibida na proxima pagina (depois de um redirect)
function definirMensagem($tipo, $texto){
    $_SESSION['mensagem'] = array('tipo' => $tipo, 'texto' => $texto);
}
