<?php

// =========================================================
// SEGURANÇA E CONEXÃO
// =========================================================

include("php/protecao_admin.php");
include("php/conexao.php");


// =========================================================
// BUSCAR CONSULTAS
// =========================================================

$sql = "
    SELECT
        consultas.id,
        consultas.data_consulta,
        consultas.hora_consulta,
        consultas.observacao,
        consultas.status,
        pacientes.nome AS paciente,
        medicos.nome AS medico
    FROM consultas
    INNER JOIN pacientes
        ON consultas.paciente_id = pacientes.id
    INNER JOIN medicos
        ON consultas.medico_id = medicos.id
    ORDER BY
        consultas.data_consulta ASC,
        consultas.hora_consulta ASC
";

$resultado = $conexao->query($sql);

if (!$resultado) {
    die("Erro ao buscar consultas: " . $conexao->error);
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

    <title>Agenda - MedCare</title>

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

        .pagina-agenda {
            width: 100%;
            min-height: calc(100vh - 80px);
            padding: 45px 20px;
        }

        .agenda-container {
            width: 94%;
            max-width: 1400px;
            margin: 0 auto;
        }


        /* =====================================================
           TÍTULO
        ====================================================== */

        .agenda-titulo {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 30px;
        }

        .icone-titulo {
            width: 62px;
            height: 62px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #1976d2;
            color: white;

            border-radius: 16px;

            font-size: 30px;

            box-shadow:
                0 8px 20px rgba(25, 118, 210, 0.18);
        }

        .agenda-titulo h1 {
            margin: 0;
            color: #123b5d;
            font-size: 30px;
            font-weight: 800;
        }

        .agenda-titulo p {
            margin-top: 5px;
            color: #718096;
            font-size: 15px;
        }


        /* =====================================================
           BOTÕES SUPERIORES
        ====================================================== */

        .acoes-topo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;

            margin-bottom: 25px;
        }

        .grupo-botoes {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 11px 18px;

            border-radius: 10px;

            text-decoration: none;

            font-size: 13px;
            font-weight: 600;

            transition: 0.2s;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-nova {
            background: #1976d2;
            color: white;
        }

        .btn-nova:hover {
            background: #125ca8;
        }

        .btn-relatorio {
            background: white;
            color: #1976d2;

            border: 1px solid #d7e4ef;
        }

        .btn-relatorio:hover {
            background: #eef6ff;
        }


        /* =====================================================
           CARD DA AGENDA
        ====================================================== */

        .agenda-card {
            background: white;

            border-radius: 18px;

            padding: 28px;

            border: 1px solid #e6edf4;

            box-shadow:
                0 5px 20px rgba(30, 60, 90, 0.08);
        }


        /* =====================================================
           CABEÇALHO DA LISTA
        ====================================================== */

        .lista-cabecalho {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 20px;

            margin-bottom: 22px;
        }

        .lista-cabecalho h2 {
            color: #173f5f;
            font-size: 20px;
            font-weight: 700;
        }

        .lista-cabecalho p {
            margin-top: 5px;
            color: #7b8794;
            font-size: 13px;
        }

        .contador-consultas {
            padding: 8px 14px;

            background: #eaf4ff;
            color: #1976d2;

            border-radius: 20px;

            font-size: 13px;
            font-weight: 600;

            white-space: nowrap;
        }


        /* =====================================================
           TABELA
        ====================================================== */

        .tabela-container {
            width: 100%;
            overflow-x: auto;

            border: 1px solid #e5edf4;
            border-radius: 13px;
        }

        table {
            width: 100%;
            min-width: 1050px;

            border-collapse: collapse;
        }

        thead {
            background: #1976d2;
            color: white;
        }

        th {
            padding: 14px 15px;

            text-align: left;

            font-size: 13px;
            font-weight: 600;

            white-space: nowrap;
        }

        td {
            padding: 15px;

            border-bottom: 1px solid #edf1f5;

            font-size: 13px;

            vertical-align: middle;
        }

        tbody tr {
            transition: 0.2s;
        }

        tbody tr:hover {
            background: #f8fbff;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }


        /* =====================================================
           DATA E HORA
        ====================================================== */

        .data-consulta {
            color: #263238;
            font-weight: 600;
        }

        .hora-consulta {
            color: #1976d2;
            font-weight: 600;
        }


        /* =====================================================
           PACIENTE / MÉDICO
        ====================================================== */

        .pessoa {
            display: flex;
            align-items: center;
            gap: 9px;

            font-weight: 600;
            color: #37474f;
        }

        .icone-pessoa {
            width: 34px;
            height: 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #eef6ff;

            border-radius: 9px;

            font-size: 16px;
        }


        /* =====================================================
           STATUS
        ====================================================== */

        .status {
            display: inline-flex;
            align-items: center;

            padding: 6px 11px;

            border-radius: 20px;

            font-size: 11px;
            font-weight: 700;

            white-space: nowrap;
        }

        .status-agendada {
            background: #fff4d6;
            color: #9a6b00;
        }

        .status-realizada {
            background: #e4f7ec;
            color: #18794e;
        }

        .status-cancelada {
            background: #ffe8e8;
            color: #c62828;
        }


        /* =====================================================
           OBSERVAÇÃO
        ====================================================== */

        .observacao {
            max-width: 230px;

            color: #667784;

            line-height: 1.5;

            word-break: break-word;
        }


        /* =====================================================
           AÇÕES
        ====================================================== */

        .acoes {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
        }

        .form-status {
            margin: 0;
            padding: 0;
        }

        .btn-acao {
            border: none;

            padding: 7px 10px;

            border-radius: 7px;

            font-family: 'Poppins', sans-serif;

            font-size: 11px;
            font-weight: 600;

            cursor: pointer;

            transition: 0.2s;
        }

        .btn-acao:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }

        .btn-realizar {
            background: #e4f7ec;
            color: #18794e;
        }

        .btn-cancelar {
            background: #ffe8e8;
            color: #c62828;
        }

        .btn-reabrir {
            background: #eaf4ff;
            color: #1976d2;
        }


        /* =====================================================
           AGENDA VAZIA
        ====================================================== */

        .agenda-vazia {
            text-align: center;

            padding: 60px 20px;
        }

        .icone-vazio {
            font-size: 45px;
            margin-bottom: 12px;
        }

        .agenda-vazia h2 {
            color: #455a64;
            font-size: 19px;
            margin-bottom: 8px;
        }

        .agenda-vazia p {
            color: #8996a3;
            font-size: 14px;
            margin-bottom: 20px;
        }


        /* =====================================================
           RESPONSIVIDADE
        ====================================================== */

        @media (max-width: 900px) {

            .agenda-container {
                width: 94%;
            }

            .acoes-topo {
                flex-direction: column;
                align-items: stretch;
            }

            .grupo-botoes {
                width: 100%;
            }

            .grupo-botoes .btn {
                flex: 1;
            }

            .agenda-card {
                padding: 20px;
            }

        }


        @media (max-width: 600px) {

            .pagina-agenda {
                padding: 30px 10px;
            }

            .agenda-titulo {
                align-items: flex-start;
            }

            .agenda-titulo h1 {
                font-size: 25px;
            }

            .agenda-titulo p {
                font-size: 14px;
            }

            .lista-cabecalho {
                flex-direction: column;
                align-items: flex-start;
            }

            .grupo-botoes {
                flex-direction: column;
            }

            .grupo-botoes .btn {
                width: 100%;
            }

        }

    </style>

</head>


<body>


<?php include("php/menu_admin.php"); ?>


<!-- =========================================================
     CONTEÚDO
========================================================= -->

<main class="pagina-agenda">

    <section class="agenda-container">


        <!-- =====================================================
             TÍTULO
        ====================================================== -->

        <div class="agenda-titulo">

            <div class="icone-titulo">
                📅
            </div>

            <div>

                <h1>Agenda de Consultas</h1>

                <p>
                    Visualize e gerencie as consultas cadastradas no MedCare.
                </p>

            </div>

        </div>


        <!-- =====================================================
             BOTÕES
        ====================================================== -->

        <div class="acoes-topo">

            <div class="grupo-botoes">

                <a
                    href="consultas.php"
                    class="btn btn-nova"
                >
                    ➕ Nova Consulta
                </a>

                <a
                    href="relatorios.php"
                    class="btn btn-relatorio"
                >
                    📊 Ver Relatórios
                </a>

            </div>

        </div>


        <!-- =====================================================
             CARD PRINCIPAL
        ====================================================== -->

        <div class="agenda-card">


            <div class="lista-cabecalho">

                <div>

                    <h2>Consultas agendadas</h2>

                    <p>
                        Acompanhe datas, horários, pacientes, médicos e status.
                    </p>

                </div>

                <div class="contador-consultas">

                    <?= $resultado->num_rows ?>

                    consulta(s)

                </div>

            </div>


            <!-- =================================================
                 TABELA
            ================================================== -->

            <div class="tabela-container">


                <?php if ($resultado->num_rows > 0): ?>


                    <table>

                        <thead>

                            <tr>

                                <th>Data</th>

                                <th>Horário</th>

                                <th>Paciente</th>

                                <th>Médico</th>

                                <th>Status</th>

                                <th>Observação</th>

                                <th>Ações</th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php while ($consulta = $resultado->fetch_assoc()): ?>


                            <?php

                            $status = $consulta['status'] ?? 'agendada';

                            $dataFormatada = date(
                                'd/m/Y',
                                strtotime($consulta['data_consulta'])
                            );

                            $horaFormatada = date(
                                'H:i',
                                strtotime($consulta['hora_consulta'])
                            );

                            ?>


                            <tr>


                                <!-- DATA -->

                                <td class="data-consulta">

                                    <?= htmlspecialchars($dataFormatada) ?>

                                </td>


                                <!-- HORÁRIO -->

                                <td class="hora-consulta">

                                    <?= htmlspecialchars($horaFormatada) ?>

                                </td>


                                <!-- PACIENTE -->

                                <td>

                                    <div class="pessoa">

                                        <div class="icone-pessoa">
                                            👤
                                        </div>

                                        <?= htmlspecialchars($consulta['paciente']) ?>

                                    </div>

                                </td>


                                <!-- MÉDICO -->

                                <td>

                                    <div class="pessoa">

                                        <div class="icone-pessoa">
                                            🩺
                                        </div>

                                        <?= htmlspecialchars($consulta['medico']) ?>

                                    </div>

                                </td>


                                <!-- STATUS -->

                                <td>


                                    <?php if ($status === 'agendada'): ?>

                                        <span class="status status-agendada">
                                            Agendada
                                        </span>


                                    <?php elseif ($status === 'realizada'): ?>

                                        <span class="status status-realizada">
                                            Realizada
                                        </span>


                                    <?php elseif ($status === 'cancelada'): ?>

                                        <span class="status status-cancelada">
                                            Cancelada
                                        </span>


                                    <?php else: ?>

                                        <span class="status">
                                            <?= htmlspecialchars($status) ?>
                                        </span>

                                    <?php endif; ?>


                                </td>


                                <!-- OBSERVAÇÃO -->

                                <td class="observacao">

                                    <?php if (!empty($consulta['observacao'])): ?>

                                        <?= htmlspecialchars($consulta['observacao']) ?>

                                    <?php else: ?>

                                        —

                                    <?php endif; ?>

                                </td>


                                <!-- AÇÕES -->

                                <td>

                                    <div class="acoes">


                                        <?php if ($status === 'agendada'): ?>


                                            <!-- REALIZAR -->

                                            <form
                                                class="form-status"
                                                action="atualizar_status_consulta.php"
                                                method="POST"
                                                onsubmit="return confirm('Deseja marcar esta consulta como realizada?');"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    value="<?= (int)$consulta['id'] ?>"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="status"
                                                    value="realizada"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn-acao btn-realizar"
                                                >
                                                    ✓ Realizar
                                                </button>

                                            </form>


                                            <!-- CANCELAR -->

                                            <form
                                                class="form-status"
                                                action="atualizar_status_consulta.php"
                                                method="POST"
                                                onsubmit="return confirm('Tem certeza que deseja cancelar esta consulta?');"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    value="<?= (int)$consulta['id'] ?>"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="status"
                                                    value="cancelada"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn-acao btn-cancelar"
                                                >
                                                    ✕ Cancelar
                                                </button>

                                            </form>


                                        <?php elseif ($status === 'realizada'): ?>


                                            <!-- REABRIR -->

                                            <form
                                                class="form-status"
                                                action="atualizar_status_consulta.php"
                                                method="POST"
                                                onsubmit="return confirm('Deseja voltar esta consulta para agendada?');"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    value="<?= (int)$consulta['id'] ?>"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="status"
                                                    value="agendada"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn-acao btn-reabrir"
                                                >
                                                    ↩ Reabrir
                                                </button>

                                            </form>


                                        <?php elseif ($status === 'cancelada'): ?>


                                            <!-- REAGENDAR -->

                                            <form
                                                class="form-status"
                                                action="atualizar_status_consulta.php"
                                                method="POST"
                                                onsubmit="return confirm('Deseja reagendar esta consulta?');"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    value="<?= (int)$consulta['id'] ?>"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="status"
                                                    value="agendada"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn-acao btn-reabrir"
                                                >
                                                    ↩ Reagendar
                                                </button>

                                            </form>


                                        <?php endif; ?>


                                    </div>

                                </td>


                            </tr>


                        <?php endwhile; ?>


                        </tbody>

                    </table>


                <?php else: ?>


                    <!-- =================================================
                         AGENDA VAZIA
                    ================================================== -->

                    <div class="agenda-vazia">

                        <div class="icone-vazio">
                            📅
                        </div>

                        <h2>
                            Nenhuma consulta encontrada
                        </h2>

                        <p>
                            Ainda não existem consultas cadastradas na agenda.
                        </p>

                        <a
                            href="consultas.php"
                            class="btn btn-nova"
                        >
                            ➕ Agendar Consulta
                        </a>

                    </div>


                <?php endif; ?>


            </div>

        </div>

    </section>

</main>


</body>

</html>
