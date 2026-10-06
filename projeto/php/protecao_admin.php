<?php

// ==========================================
// PROTEÇÃO DA ÁREA ADMINISTRATIVA
// ==========================================

// Iniciar sessão somente se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ==========================================
// VERIFICAR SE ESTÁ LOGADO
// ==========================================

if (!isset($_SESSION['usuario_id'])) {

    header("Location: /projeto/login.php");
    exit;
}


// ==========================================
// VERIFICAR SE É ADMINISTRADOR
// ==========================================

if (
    !isset($_SESSION['usuario_tipo']) ||
    $_SESSION['usuario_tipo'] !== 'admin'
) {

    header("Location: /projeto/paciente.php");
    exit;
}

?>
