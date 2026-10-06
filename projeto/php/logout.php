<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Destruir todas as informações da sessão

$_SESSION = [];


// Destruir a sessão

session_destroy();


// Voltar para o login

header("Location: ../login.php");

exit;

?>