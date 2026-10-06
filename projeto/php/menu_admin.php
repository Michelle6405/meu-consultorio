<?php
// ==========================================
// MENU ADMINISTRATIVO - MEDCARE
// ==========================================

$paginaAtual = basename($_SERVER['PHP_SELF']);
?>

<header class="header-principal">

    <!-- LOGO -->
    <a href="dashboard.php" class="logo">
        <span class="logo-icone">🏥</span>
        <span>MedCare</span>
    </a>

    <!-- MENU -->
    <nav class="menu-principal">

        <a
            href="dashboard.php"
            class="<?php echo ($paginaAtual === 'dashboard.php') ? 'ativo' : ''; ?>"
        >
            🏠 Início
        </a>

        <a
            href="pacientes.php"
            class="<?php echo ($paginaAtual === 'pacientes.php') ? 'ativo' : ''; ?>"
        >
            👥 Pacientes
        </a>

        <a
            href="medicos.php"
            class="<?php echo ($paginaAtual === 'medicos.php') ? 'ativo' : ''; ?>"
        >
            👨‍⚕️ Médicos
        </a>

        <a
            href="consultas.php"
            class="<?php echo ($paginaAtual === 'consultas.php') ? 'ativo' : ''; ?>"
        >
            📅 Consultas
        </a>

        <a
            href="especialidades.php"
            class="<?php echo ($paginaAtual === 'especialidades.php') ? 'ativo' : ''; ?>"
        >
            🩺 Especialidades
        </a>

        <a
            href="agenda.php"
            class="<?php echo ($paginaAtual === 'agenda.php') ? 'ativo' : ''; ?>"
        >
            🗓️ Agenda
        </a>

        <a
            href="relatorios.php"
            class="<?php echo ($paginaAtual === 'relatorios.php') ? 'ativo' : ''; ?>"
        >
            📊 Relatórios
        </a>

        <a href="php/logout.php" class="btn-sair">
            🚪 Sair
        </a>

    </nav>

</header>