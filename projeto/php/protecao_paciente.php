<?php

// =========================================================
// INICIAR SESSÃO
// =========================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// =========================================================
// VERIFICAR LOGIN
// =========================================================

if (!isset($_SESSION['usuario_id'])) {

    $_SESSION['pagina_destino'] = 'agendar.php';

    header("Location: /projeto/login.php");

    exit;
}


// =========================================================
// VERIFICAR TIPO DE USUÁRIO
// =========================================================

if (
    !isset($_SESSION['usuario_tipo']) ||
    $_SESSION['usuario_tipo'] !== 'paciente'
) {

    // Se for administrador, manda para o painel do admin
    if (
        isset($_SESSION['usuario_tipo']) &&
        $_SESSION['usuario_tipo'] === 'admin'
    ) {

        header("Location: /projeto/dashboard.php");

        exit;
    }


    // Qualquer outro tipo volta para login
    session_unset();
    session_destroy();

    header("Location: /projeto/login.php");

    exit;
}

?>