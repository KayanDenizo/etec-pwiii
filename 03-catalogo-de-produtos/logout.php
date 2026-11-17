<?php
//encerra a sessao e volta para o login
session_start();
$_SESSION = array();
session_destroy();

header("Location: index.php");
exit;
