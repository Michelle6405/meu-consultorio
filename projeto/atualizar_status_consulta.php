<?php

// ==========================================
// SEGURANÇA E CONEXÃO
// ==========================================

include("php/protecao_admin.php");
include("php/conexao.php");


// ==========================================
// BUSCAR CONSULTAS
// ==========================================

$sql = "SELECT
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
            consultas.hora_consulta ASC";

$resultado = mysqli_query($conexao, $sql);


// ==========================================
// VERIFICAR ERRO NA CONSULTA
// ==========================================

if (!$resultado) {
    die("Erro ao buscar consultas: " . mysqli_error($conexao));
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Agenda de Consultas - MedCare</title>


    <style>

        /* ==========================================
           RESET
           ========================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        /* ==========================================
           BODY
           ========================================== */

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f7fb;
            color: #333;
            min-height: 100vh;
        }


        /* ==========================================
           CONTAINER PRINCIPAL
           ========================================== */

        .container {
            width: 95%;
            max-width: 1400px;
            margin: 30px auto;
        }


        /* ==========================================
           CABEÇALHO
           ========================================== */

        .cabecalho {
            background: #ffffff;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;

            box-shadow:
                0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .cabecalho h1 {
            color: #0d6efd;
            font-size: 28px;
            margin-bottom: 8px;
        }

        .cabecalho p {
            color: #666;
            font-size: 15px;
        }


        /* ==========================================
           ÁREA DE BOTÕES DO TOPO
           ========================================== */

        .acoes-topo {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 10px;

            margin-bottom: 20px;

            flex-wrap: wrap;
        }

        .grupo-botoes {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }


        /* ==========================================
           BOTÕES
           ========================================== */

        .btn {
            display: inline-block;

            padding: 11px 18px;

            border-radius: 7px;

            text-decoration: none;

            border: none;

            cursor: pointer;

            font-size: 14px;

            font-weight: bold;

            transition: 0.2s;
        }

        .btn:hover {
            opacity: 0.85;
            transform: translateY(-1px);
        }


        /* NOVA CONSULTA */

        .btn-nova {
            background: #0d6efd;
            color: #ffffff;
        }


        /* RELATÓRIOS */

        .btn-relatorio {
            background: #6c757d;
            color: #ffffff;
        }


        /* ==========================================
           TABELA
           ========================================== */

        .tabela-container {
            background: #ffffff;

            border-radius: 12px;

            box-shadow:
                0 3px 12px rgba(0, 0, 0, 0.08);

            overflow-x: auto;
        }


        table {
            width: 100%;

            border-collapse: collapse;

            min-width: 1100px;
        }


        /* CABEÇALHO DA TABELA */

        thead {
            background: #0d6efd;

            color: #ffffff;
        }


        th {
            padding: 15px 12px;

            text-align: left;

            font-size: 14px;

            white-space: nowrap;
        }


        /* CÉLULAS */

        td {
            padding: 14px 12px;

            text-align: left;

            font-size: 14px;

            border-bottom: 1px solid #eeeeee;

            vertical-align: middle;
        }


        /* EFEITO AO PASSAR O MOUSE */

        tbody tr:hover {
            background: #f8faff;
        }


        /* ==========================================
           STATUS
           ========================================== */

        .status {
            display: inline-block;

            padding: 6px 11px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: bold;

            white-space: nowrap;
        }


        /* AGENDADA */

        .status-agendada {
            background: #fff3cd;

            color: #856404;
        }


        /* REALIZADA */

        .status-realizada {
            background: #d1e7dd;

            color: #0f5132;
        }


        /* CANCELADA */

        .status-cancelada {
            background: #f8d7da;

            color: #842029;
        }


        /* ==========================================
           OBSERVAÇÃO
           ========================================== */

        .observacao {
            max-width: 230px;

            word-break: break-word;

            color: #555;
        }


        /* ==========================================
           AÇÕES DA CONSULTA
           ========================================== */

        .acoes {
            display: flex;

            gap: 7px;

            flex-wrap: wrap;

            align-items: center;
        }


        .form-status {
            display: inline;

            margin: 0;

            padding: 0;
        }


        /* BOTÃO DE AÇÃO */

        .btn-acao {
            display: inline-block;

            padding: 7px 10px;

            border: none;

            border-radius: 6px;

            cursor: pointer;

            font-size: 12px;

            font-weight: bold;

            white-space: nowrap;

            transition: 0.2s;
        }


        .btn-acao:hover {
            opacity: 0.85;

            transform: translateY(-1px);
        }


        /* REALIZAR */

        .btn-realizar {
            background: #198754;

            color: #ffffff;
        }


        /* CANCELAR */

        .btn-cancelar {
            background: #dc3545;

            color: #ffffff;
        }


        /* REABRIR / REAGENDAR */

        .btn-reabrir {
            background: #0d6efd;

            color: #ffffff;
        }


        /* ==========================================
           AGENDA VAZIA
           ========================================== */

        .agenda-vazia {
            text-align: center;

            padding: 70px 20px;
        }


        .agenda-vazia h2 {
            color: #555;

            margin-bottom: 10px;

            font-size: 22px;
        }


        .agenda-vazia p {
            color: #777;

            margin-bottom: 20px;
        }


        /* ==========================================
           RODAPÉ DA TABELA
           ========================================== */

        .rodape-agenda {
            padding: 15px 20px;

            border-top: 1px solid #eeeeee;

            color: #777;

            font-size: 13px;

            background: #fafafa;
        }


        /* ==========================================
           RESPONSIVIDADE
           ========================================== */

        @media (max-width: 768px) {

            .container {
                width: 94%;

                margin: 20px auto;
            }


            .cabecalho {
                padding: 20px;
            }


            .cabecalho h1 {
                font-size: 23px;
            }


            .acoes-topo {
                flex-direction: column;

                align-items: stretch;
            }


            .grupo-botoes {
                width: 100%;

                flex-direction: column;
            }


            .grupo-botoes .btn {
                width: 100%;

                text-align: center;
            }

        }

    </style>

</head>


<body>


<div class="container">


    <!-- ==========================================
         CABEÇALHO
         ========================================== -->

    <div class="cabecalho">

        <h1>
            📅 Agenda de Consultas
        </h1>

        <p>
            Visualize, realize, cancele ou reagende as consultas.
        </p>

    </div>


    <!-- ==========================================
         BOTÕES SUPERIORES
         ========================================== -->

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
                📊 Relatórios
            </a>

        </div>


    </div>


    <!-- ==========================================
         TABELA
         ========================================== -->

    <div class="tabela-container">


        <?php if (mysqli_num_rows($resultado) > 0): ?>


            <table>


                <!-- ==================================
                     CABEÇALHO
                     ================================== -->

                <thead>

                    <tr>

                        <th>
                            Data
                        </th>

                        <th>
                            Horário
                        </th>

                        <th>
                            Paciente
                        </th>

                        <th>
                            Médico
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Observação
                        </th>

                        <th>
                            Ações
                        </th>

                    </tr>

                </thead>


                <!-- ==================================
                     CONSULTAS
                     ================================== -->

                <tbody>


                <?php while ($consulta = mysqli_fetch_assoc($resultado)): ?>


                    <?php

                    // ==================================
                    // STATUS
                    // ==================================

                    $status = $consulta['status'] ?? 'agendada';


                    // ==================================
                    // FORMATAR DATA
                    // ==================================

                    if (!empty($consulta['data_consulta'])) {

                        $dataFormatada = date(
                            'd/m/Y',
                            strtotime($consulta['data_consulta'])
                        );

                    } else {

                        $dataFormatada = '-';

                    }


                    // ==================================
                    // FORMATAR HORÁRIO
                    // ==================================

                    if (!empty($consulta['hora_consulta'])) {

                        $horaFormatada = date(
                            'H:i',
                            strtotime($consulta['hora_consulta'])
                        );

                    } else {

                        $horaFormatada = '-';

                    }

                    ?>


                    <tr>


                        <!-- ============================
                             DATA
                             ============================ -->

                        <td>

                            <?= htmlspecialchars($dataFormatada) ?>

                        </td>


                        <!-- ============================
                             HORÁRIO
                             ============================ -->

                        <td>

                            <?= htmlspecialchars($horaFormatada) ?>

                        </td>


                        <!-- ============================
                             PACIENTE
                             ============================ -->

                        <td>

                            <?= htmlspecialchars(
                                $consulta['paciente']
                            ) ?>

                        </td>


                        <!-- ============================
                             MÉDICO
                             ============================ -->

                        <td>

                            <?= htmlspecialchars(
                                $consulta['medico']
                            ) ?>

                        </td>


                        <!-- ============================
                             STATUS
                             ============================ -->

                        <td>


                            <?php if ($status === 'agendada'): ?>


                                <span class="status status-agendada">

                                    🟡 Agendada

                                </span>


                            <?php elseif ($status === 'realizada'): ?>


                                <span class="status status-realizada">

                                    🟢 Realizada

                                </span>


                            <?php elseif ($status === 'cancelada'): ?>


                                <span class="status status-cancelada">

                                    🔴 Cancelada

                                </span>


                            <?php else: ?>


                                <span class="status">

                                    <?= htmlspecialchars($status) ?>

                                </span>


                            <?php endif; ?>


                        </td>


                        <!-- ============================
                             OBSERVAÇÃO
                             ============================ -->

                        <td class="observacao">


                            <?php if (!empty($consulta['observacao'])): ?>


                                <?= htmlspecialchars(
                                    $consulta['observacao']
                                ) ?>


                            <?php else: ?>


                                —

                            <?php endif; ?>


                        </td>


                        <!-- ============================
                             AÇÕES
                             ============================ -->

                        <td>


                            <div class="acoes">


                                <?php if ($status === 'agendada'): ?>


                                    <!-- ======================
                                         REALIZAR
                                         ====================== -->

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


                                    <!-- ======================
                                         CANCELAR
                                         ====================== -->

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


                                    <!-- ======================
                                         REABRIR
                                         ====================== -->

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


                                    <!-- ======================
                                         REAGENDAR
                                         ====================== -->

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


            <!-- ======================================
                 RODAPÉ
                 ====================================== -->

            <div class="rodape-agenda">

                As consultas são organizadas por data e horário.

            </div>


        <?php else: ?>


            <!-- ======================================
                 AGENDA VAZIA
                 ====================================== -->

            <div class="agenda-vazia">


                <h2>
                    📅 Nenhuma consulta encontrada
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


</body>

</html>


<?php

// ==========================================
// FECHAR CONEXÃO
// ==========================================

mysqli_close($conexao);

?>