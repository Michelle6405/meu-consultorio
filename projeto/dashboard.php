<?php

// =========================================================
// SEGURANÇA E CONEXÃO
// =========================================================

include("php/protecao_admin.php");
include("php/conexao.php");


// =========================================================
// DADOS DO ADMINISTRADOR
// =========================================================

$nomeAdministrador = $_SESSION['usuario_nome'] ?? 'Administrador';


// =========================================================
// CONTADORES
// =========================================================

// PACIENTES
$sqlPacientes = "
    SELECT COUNT(*) AS total
    FROM pacientes
";

$resultadoPacientes = $conexao->query($sqlPacientes);
$totalPacientes = $resultadoPacientes->fetch_assoc()['total'];


// MÉDICOS
$sqlMedicos = "
    SELECT COUNT(*) AS total
    FROM medicos
";

$resultadoMedicos = $conexao->query($sqlMedicos);
$totalMedicos = $resultadoMedicos->fetch_assoc()['total'];


// ESPECIALIDADES
$sqlEspecialidades = "
    SELECT COUNT(*) AS total
    FROM especialidades
";

$resultadoEspecialidades = $conexao->query($sqlEspecialidades);
$totalEspecialidades = $resultadoEspecialidades->fetch_assoc()['total'];


// CONSULTAS DE HOJE
$sqlHoje = "
    SELECT COUNT(*) AS total
    FROM consultas
    WHERE data_consulta = CURDATE()
";

$resultadoHoje = $conexao->query($sqlHoje);
$totalHoje = $resultadoHoje->fetch_assoc()['total'];


// CONSULTAS AGENDADAS
$sqlAgendadas = "
    SELECT COUNT(*) AS total
    FROM consultas
    WHERE status = 'agendada'
";

$resultadoAgendadas = $conexao->query($sqlAgendadas);
$totalAgendadas = $resultadoAgendadas->fetch_assoc()['total'];


// =========================================================
// PRÓXIMAS CONSULTAS
// =========================================================

$sqlProximas = "
    SELECT
        consultas.id,
        consultas.data_consulta,
        consultas.hora_consulta,
        consultas.status,
        pacientes.nome AS paciente,
        medicos.nome AS medico
    FROM consultas

    INNER JOIN pacientes
        ON consultas.paciente_id = pacientes.id

    INNER JOIN medicos
        ON consultas.medico_id = medicos.id

    WHERE
        consultas.status = 'agendada'
        AND CONCAT(
            consultas.data_consulta,
            ' ',
            consultas.hora_consulta
        ) >= NOW()

    ORDER BY
        consultas.data_consulta ASC,
        consultas.hora_consulta ASC

    LIMIT 5
";

$resultadoProximas = $conexao->query($sqlProximas);


// =========================================================
// CONSULTAS RECENTES
// =========================================================

$sqlRecentes = "
    SELECT
        consultas.data_consulta,
        consultas.hora_consulta,
        consultas.status,
        pacientes.nome AS paciente
    FROM consultas

    INNER JOIN pacientes
        ON consultas.paciente_id = pacientes.id

    ORDER BY
        consultas.id DESC

    LIMIT 4
";

$resultadoRecentes = $conexao->query($sqlRecentes);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Início - MedCare</title>


    <!-- =====================================================
         GOOGLE FONT
    ====================================================== -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <!-- =====================================================
         CSS PRINCIPAL
    ====================================================== -->

    <link rel="stylesheet" href="css/style.css">


    <style>

        /* =====================================================
           CONFIGURAÇÕES GERAIS
        ====================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f8fc;
            color: #263238;
        }


        /* =====================================================
           CONTAINER
        ====================================================== */

        .pagina-dashboard {
            width: 100%;
            min-height: calc(100vh - 80px);
            padding: 35px 20px 50px;
        }

        .dashboard-container {
            width: 94%;
            max-width: 1400px;
            margin: 0 auto;
        }


        /* =====================================================
           BANNER DE BOAS-VINDAS
        ====================================================== */

        .boas-vindas {
            position: relative;

            background: linear-gradient(
                135deg,
                #1976d2,
                #2196f3
            );

            border-radius: 20px;

            padding: 30px 35px;

            color: white;

            margin-bottom: 25px;

            overflow: hidden;

            box-shadow:
                0 10px 25px rgba(25, 118, 210, 0.18);
        }

        .boas-vindas::after {
            content: "🏥";

            position: absolute;

            right: 35px;
            bottom: -15px;

            font-size: 110px;

            opacity: 0.12;
        }

        .boas-vindas h1 {
            font-size: 27px;
            font-weight: 700;

            margin-bottom: 6px;
        }

        .boas-vindas p {
            font-size: 14px;
            opacity: 0.92;
        }

        .boas-vindas strong {
            font-weight: 700;
        }


        /* =====================================================
           INDICADORES
        ====================================================== */

        .indicadores {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 18px;

            margin-bottom: 25px;
        }

        .indicador {
            background: white;

            border: 1px solid #e6edf4;

            border-radius: 16px;

            padding: 20px;

            display: flex;

            align-items: center;

            gap: 15px;

            box-shadow:
                0 5px 18px rgba(30, 60, 90, 0.06);

            transition: 0.2s;
        }

        .indicador:hover {
            transform: translateY(-2px);

            box-shadow:
                0 8px 22px rgba(30, 60, 90, 0.09);
        }

        .icone-indicador {
            width: 50px;
            height: 50px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #eef6ff;

            border-radius: 12px;

            font-size: 22px;

            flex-shrink: 0;
        }

        .indicador p {
            color: #718096;

            font-size: 12px;

            margin-bottom: 2px;
        }

        .indicador h2 {
            color: #1976d2;

            font-size: 24px;

            font-weight: 700;
        }


        /* =====================================================
           GRID PRINCIPAL
        ====================================================== */

        .dashboard-grid {
            display: grid;

            grid-template-columns:
                2fr 1fr;

            gap: 22px;
        }


        /* =====================================================
           CARDS
        ====================================================== */

        .dashboard-card {
            background: white;

            border: 1px solid #e6edf4;

            border-radius: 18px;

            padding: 25px;

            box-shadow:
                0 5px 18px rgba(30, 60, 90, 0.06);
        }

        .card-cabecalho {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            margin-bottom: 20px;
        }

        .card-cabecalho h2 {
            color: #173f5f;

            font-size: 18px;

            font-weight: 700;
        }

        .card-cabecalho p {
            color: #7b8794;

            font-size: 12px;

            margin-top: 3px;
        }

        .link-card {
            color: #1976d2;

            text-decoration: none;

            font-size: 12px;

            font-weight: 600;

            white-space: nowrap;
        }

        .link-card:hover {
            text-decoration: underline;
        }


        /* =====================================================
           PRÓXIMAS CONSULTAS
        ====================================================== */

        .consulta {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            padding: 15px 0;

            border-bottom: 1px solid #edf1f5;
        }

        .consulta:last-child {
            border-bottom: none;
        }

        .consulta-esquerda {
            display: flex;

            align-items: center;

            gap: 13px;

            min-width: 0;
        }

        .icone-consulta {
            width: 42px;
            height: 42px;

            display: flex;

            align-items: center;
            justify-content: center;

            background: #eef6ff;

            border-radius: 10px;

            font-size: 18px;

            flex-shrink: 0;
        }

        .consulta-info {
            min-width: 0;
        }

        .consulta-info h3 {
            color: #37474f;

            font-size: 13px;

            font-weight: 600;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;
        }

        .consulta-info p {
            color: #8996a3;

            font-size: 11px;

            margin-top: 3px;
        }

        .consulta-horario {
            text-align: right;

            flex-shrink: 0;
        }

        .consulta-horario strong {
            display: block;

            color: #1976d2;

            font-size: 13px;
        }

        .consulta-horario span {
            color: #8996a3;

            font-size: 11px;
        }


        /* =====================================================
           AÇÕES RÁPIDAS
        ====================================================== */

        .acoes-rapidas {
            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 12px;
        }

        .acao-rapida {
            display: flex;

            align-items: center;

            gap: 10px;

            padding: 14px;

            border-radius: 12px;

            background: #f7faff;

            border: 1px solid #e2edf7;

            text-decoration: none;

            color: #37474f;

            transition: 0.2s;
        }

        .acao-rapida:hover {
            background: #eef6ff;

            border-color: #c9def2;

            transform: translateY(-1px);
        }

        .icone-acao {
            width: 38px;
            height: 38px;

            display: flex;

            align-items: center;
            justify-content: center;

            background: white;

            border-radius: 9px;

            font-size: 17px;

            box-shadow:
                0 2px 7px rgba(30, 60, 90, 0.06);
        }

        .acao-rapida span {
            font-size: 12px;

            font-weight: 600;
        }


        /* =====================================================
           RESUMO DO DIA
        ====================================================== */

        .resumo-item {
            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 13px 0;

            border-bottom: 1px solid #edf1f5;
        }

        .resumo-item:last-child {
            border-bottom: none;
        }

        .resumo-item span:first-child {
            color: #667784;

            font-size: 13px;
        }

        .resumo-valor {
            color: #1976d2;

            font-size: 16px;

            font-weight: 700;
        }


        /* =====================================================
           ESTADOS
        ====================================================== */

        .estado-vazio {
            text-align: center;

            padding: 30px 10px;

            color: #8996a3;
        }

        .estado-vazio .icone {
            font-size: 32px;

            margin-bottom: 8px;
        }

        .estado-vazio p {
            font-size: 13px;
        }


        /* =====================================================
           CONSULTAS RECENTES
        ====================================================== */

        .atividade {
            display: flex;

            align-items: center;

            gap: 12px;

            padding: 12px 0;

            border-bottom: 1px solid #edf1f5;
        }

        .atividade:last-child {
            border-bottom: none;
        }

        .atividade-icone {
            width: 36px;
            height: 36px;

            display: flex;

            align-items: center;
            justify-content: center;

            background: #eef6ff;

            border-radius: 9px;

            font-size: 15px;
        }

        .atividade-info {
            flex: 1;
        }

        .atividade-info strong {
            display: block;

            color: #455a64;

            font-size: 12px;
        }

        .atividade-info span {
            color: #8996a3;

            font-size: 10px;
        }


        /* =====================================================
           RESPONSIVIDADE
        ====================================================== */

        @media (max-width: 1000px) {

            .indicadores {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 600px) {

            .pagina-dashboard {
                padding: 25px 10px 40px;
            }

            .dashboard-container {
                width: 94%;
            }

            .boas-vindas {
                padding: 25px;
            }

            .boas-vindas h1 {
                font-size: 23px;
            }

            .boas-vindas::after {
                right: 10px;
                font-size: 75px;
            }

            .indicadores {
                grid-template-columns: 1fr;
            }

            .dashboard-card {
                padding: 20px;
            }

            .acoes-rapidas {
                grid-template-columns: 1fr;
            }

            .consulta {
                align-items: flex-start;
            }

        }

    </style>

</head>


<body>


<?php include("php/menu_admin.php"); ?>


<!-- =========================================================
     DASHBOARD
========================================================= -->

<main class="pagina-dashboard">

    <section class="dashboard-container">


        <!-- =====================================================
             BOAS-VINDAS
        ====================================================== -->

        <div class="boas-vindas">

            <h1>
                Olá, <?= htmlspecialchars($nomeAdministrador) ?>! 👋
            </h1>

            <p>
                Bem-vindo ao painel administrativo do
                <strong>MedCare</strong>.
                Aqui você acompanha o funcionamento da clínica.
            </p>

        </div>


        <!-- =====================================================
             INDICADORES
        ====================================================== -->

        <div class="indicadores">


            <div class="indicador">

                <div class="icone-indicador">
                    👥
                </div>

                <div>

                    <p>Pacientes cadastrados</p>

                    <h2>
                        <?= $totalPacientes ?>
                    </h2>

                </div>

            </div>


            <div class="indicador">

                <div class="icone-indicador">
                    🩺
                </div>

                <div>

                    <p>Médicos cadastrados</p>

                    <h2>
                        <?= $totalMedicos ?>
                    </h2>

                </div>

            </div>


            <div class="indicador">

                <div class="icone-indicador">
                    🏥
                </div>

                <div>

                    <p>Especialidades</p>

                    <h2>
                        <?= $totalEspecialidades ?>
                    </h2>

                </div>

            </div>


            <div class="indicador">

                <div class="icone-indicador">
                    📅
                </div>

                <div>

                    <p>Consultas hoje</p>

                    <h2>
                        <?= $totalHoje ?>
                    </h2>

                </div>

            </div>


        </div>


        <!-- =====================================================
             GRID
        ====================================================== -->

        <div class="dashboard-grid">


            <!-- =================================================
                 PRÓXIMAS CONSULTAS
            ================================================== -->

            <div class="dashboard-card">

                <div class="card-cabecalho">

                    <div>

                        <h2>📅 Próximas consultas</h2>

                        <p>
                            As próximas consultas agendadas.
                        </p>

                    </div>

                    <a
                        href="agenda.php"
                        class="link-card"
                    >
                        Ver agenda →
                    </a>

                </div>


                <?php if ($resultadoProximas->num_rows > 0): ?>


                    <?php while ($consulta = $resultadoProximas->fetch_assoc()): ?>


                        <?php

                        $data = date(
                            'd/m/Y',
                            strtotime($consulta['data_consulta'])
                        );

                        $hora = date(
                            'H:i',
                            strtotime($consulta['hora_consulta'])
                        );

                        ?>


                        <div class="consulta">


                            <div class="consulta-esquerda">

                                <div class="icone-consulta">
                                    👤
                                </div>

                                <div class="consulta-info">

                                    <h3>
                                        <?= htmlspecialchars($consulta['paciente']) ?>
                                    </h3>

                                    <p>
                                        🩺 <?= htmlspecialchars($consulta['medico']) ?>
                                    </p>

                                </div>

                            </div>


                            <div class="consulta-horario">

                                <strong>
                                    <?= $hora ?>
                                </strong>

                                <span>
                                    <?= $data ?>
                                </span>

                            </div>


                        </div>


                    <?php endwhile; ?>


                <?php else: ?>


                    <div class="estado-vazio">

                        <div class="icone">
                            📅
                        </div>

                        <p>
                            Não há próximas consultas agendadas.
                        </p>

                    </div>


                <?php endif; ?>


            </div>


            <!-- =================================================
                 AÇÕES RÁPIDAS
            ================================================== -->

            <div class="dashboard-card">

                <div class="card-cabecalho">

                    <div>

                        <h2>⚡ Acesso rápido</h2>

                        <p>
                            Atalhos para tarefas frequentes.
                        </p>

                    </div>

                </div>


                <div class="acoes-rapidas">


                    <a
                        href="pacientes.php"
                        class="acao-rapida"
                    >

                        <div class="icone-acao">
                            👥
                        </div>

                        <span>
                            Pacientes
                        </span>

                    </a>


                    <a
                        href="medicos.php"
                        class="acao-rapida"
                    >

                        <div class="icone-acao">
                            🩺
                        </div>

                        <span>
                            Médicos
                        </span>

                    </a>


                    <a
                        href="consultas.php"
                        class="acao-rapida"
                    >

                        <div class="icone-acao">
                            📅
                        </div>

                        <span>
                            Nova consulta
                        </span>

                    </a>


                    <a
                        href="especialidades.php"
                        class="acao-rapida"
                    >

                        <div class="icone-acao">
                            🏥
                        </div>

                        <span>
                            Especialidades
                        </span>

                    </a>


                </div>


                <div style="margin-top: 20px;">

                    <div class="resumo-item">

                        <span>
                            Consultas agendadas
                        </span>

                        <span class="resumo-valor">
                            <?= $totalAgendadas ?>
                        </span>

                    </div>


                    <div class="resumo-item">

                        <span>
                            Consultas hoje
                        </span>

                        <span class="resumo-valor">
                            <?= $totalHoje ?>
                        </span>

                    </div>

                </div>


            </div>


            <!-- =================================================
                 CONSULTAS RECENTES
            ================================================== -->

            <div class="dashboard-card">

                <div class="card-cabecalho">

                    <div>

                        <h2>🕐 Consultas recentes</h2>

                        <p>
                            Últimos registros realizados.
                        </p>

                    </div>

                    <a
                        href="consultas.php"
                        class="link-card"
                    >
                        Ver consultas →
                    </a>

                </div>


                <?php if ($resultadoRecentes->num_rows > 0): ?>


                    <?php while ($recente = $resultadoRecentes->fetch_assoc()): ?>


                        <?php

                        $dataRecente = date(
                            'd/m/Y',
                            strtotime($recente['data_consulta'])
                        );

                        ?>


                        <div class="atividade">

                            <div class="atividade-icone">
                                📋
                            </div>

                            <div class="atividade-info">

                                <strong>
                                    <?= htmlspecialchars($recente['paciente']) ?>
                                </strong>

                                <span>
                                    <?= $dataRecente ?>
                                    •
                                    <?= htmlspecialchars($recente['status']) ?>
                                </span>

                            </div>

                        </div>


                    <?php endwhile; ?>


                <?php else: ?>


                    <div class="estado-vazio">

                        <div class="icone">
                            📋
                        </div>

                        <p>
                            Ainda não existem consultas registradas.
                        </p>

                    </div>


                <?php endif; ?>


            </div>


            <!-- =================================================
                 RESUMO DO DIA
            ================================================== -->

            <div class="dashboard-card">

                <div class="card-cabecalho">

                    <div>

                        <h2>📌 Resumo do sistema</h2>

                        <p>
                            Visão rápida da situação atual.
                        </p>

                    </div>

                </div>


                <div class="resumo-item">

                    <span>
                        Pacientes
                    </span>

                    <span class="resumo-valor">
                        <?= $totalPacientes ?>
                    </span>

                </div>


                <div class="resumo-item">

                    <span>
                        Médicos
                    </span>

                    <span class="resumo-valor">
                        <?= $totalMedicos ?>
                    </span>

                </div>


                <div class="resumo-item">

                    <span>
                        Especialidades
                    </span>

                    <span class="resumo-valor">
                        <?= $totalEspecialidades ?>
                    </span>

                </div>


                <div class="resumo-item">

                    <span>
                        Consultas agendadas
                    </span>

                    <span class="resumo-valor">
                        <?= $totalAgendadas ?>
                    </span>

                </div>


            </div>


        </div>

    </section>

</main>


</body>

</html>