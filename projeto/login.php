<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ==========================================
// DESTINO APÓS O LOGIN
// ==========================================

$redirect = $_GET['redirect'] ?? '';


// ==========================================
// SE JÁ ESTIVER LOGADO
// ==========================================

if (isset($_SESSION['usuario_id'])) {

    if (
        isset($_SESSION['usuario_tipo']) &&
        $_SESSION['usuario_tipo'] === 'admin'
    ) {

        header("Location: dashboard.php");
        exit;

    } else {

        if ($redirect === 'agendar.php') {

            header("Location: agendar.php");
            exit;

        }

        header("Location: paciente.php");
        exit;
    }
}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Entrar - MedCare</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

</head>

<body>

<!-- =====================================================
     LOGIN
===================================================== -->

<main class="login-container">

    <div class="login-box">

        <div class="login-logo">
            🏥 MedCare
        </div>

        <h1>
            Acesse sua conta
        </h1>

        <p>
            Entre para acessar os recursos do MedCare.
        </p>


        <!-- FORMULÁRIO -->

        <form
            action="verificarLogin.php"
            method="POST"
        >

            <!--
                Guarda o destino escolhido antes do login.
            -->

            <input
                type="hidden"
                name="redirect"
                value="<?= htmlspecialchars($redirect) ?>"
            >


            <div class="form-grupo">

                <label for="email">
                    E-mail
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Digite seu e-mail"
                    required
                >

            </div>


            <div class="form-grupo">

                <label for="senha">
                    Senha
                </label>

                <input
                    type="password"
                    id="senha"
                    name="senha"
                    placeholder="Digite sua senha"
                    required
                >

            </div>


            <button
                type="submit"
                class="btn"
            >
                🔐 Entrar
            </button>

        </form>


        <a
            href="index.php"
            class="login-voltar"
        >
            ← Voltar para o início
        </a>

    </div>

</main>

</body>

</html>
