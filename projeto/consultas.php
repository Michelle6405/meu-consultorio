<?php

// =========================================================
// SEGURANÇA E CONEXÃO
// =========================================================

include("php/protecao_admin.php");
include("php/conexao.php");


// =========================================================
// BUSCAR MÉDICOS
// =========================================================

$sqlMedicos = "
    SELECT
        medicos.id,
        medicos.nome,
        especialidades.nome AS especialidade
    FROM medicos
    LEFT JOIN especialidades
        ON medicos.especialidade_id = especialidades.id
    ORDER BY medicos.nome ASC
";

$resultadoMedicos = mysqli_query($conexao, $sqlMedicos);


// =========================================================
// BUSCAR CONSULTAS
// =========================================================

$sql = "
    SELECT
        consultas.*,
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

$resultado = mysqli_query($conexao, $sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Consultas - MedCare</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>

        /* =====================================================
           CONSULTAS - MEDCARE
        ===================================================== */

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f8fc;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }

        .container-consultas {
            width: 92%;
            max-width: 1400px;
            margin: 40px auto;
        }

        /* =====================================================
           TÍTULO
        ===================================================== */

        .titulo-pagina {
            margin-bottom: 30px;
        }

        .titulo-pagina h1 {
            margin: 0;
            color: #1976D2;
            font-size: 32px;
            font-weight: 700;
        }

        .titulo-pagina p {
            margin-top: 8px;
            color: #777;
            font-size: 15px;
        }

        /* =====================================================
           CARDS
        ===================================================== */

        .card-consulta {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
        }

        .card-consulta h2 {
            margin: 0 0 25px;
            color: #333;
            font-size: 22px;
            font-weight: 700;
        }

        /* =====================================================
           FORMULÁRIO
        ===================================================== */

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .grupo {
            display: flex;
            flex-direction: column;
        }

        .grupo label {
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #444;
        }

        .grupo input,
        .grupo select,
        .grupo textarea {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #d8e0e8;
            border-radius: 9px;
            outline: none;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            background: #fff;
            transition: 0.2s;
        }

        .grupo input:focus,
        .grupo select:focus,
        .grupo textarea:focus {
            border-color: #1976D2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.10);
        }

        .grupo textarea {
            min-height: 110px;
            resize: vertical;
        }

        .grupo-completo {
            grid-column: 1 / -1;
        }

        /* =====================================================
           BOTÃO
        ===================================================== */

        .botao-form {
            margin-top: 25px;
        }

        .botao-form button {
            border: none;
            background: #1976D2;
            color: white;
            padding: 13px 25px;
            border-radius: 9px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .botao-form button:hover {
            background: #125ca8;
            transform: translateY(-1px);
        }

        /* =====================================================
           TABELA
        ===================================================== */

        .tabela-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .tabela-consultas {
            width: 100%;
            border-collapse: collapse;
            min-width: 950px;
        }

        .tabela-consultas th {
            background: #1976D2;
            color: white;
            padding: 14px 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
        }

        .tabela-consultas th:first-child {
            border-radius: 8px 0 0 0;
        }

        .tabela-consultas th:last-child {
            border-radius: 0 8px 0 0;
        }

        .tabela-consultas td {
            padding: 14px 12px;
            border-bottom: 1px solid #e9eef3;
            font-size: 13px;
            color: #555;
            vertical-align: middle;
        }

        .tabela-consultas tbody tr:hover {
            background: #f8fbff;
        }

        /* =====================================================
           STATUS
        ===================================================== */

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-agendada {
            background: #e3f2fd;
            color: #1976D2;
        }

        .status-realizada {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-cancelada {
            background: #ffebee;
            color: #c62828;
        }

        /* =====================================================
           AÇÕES
        ===================================================== */

        .acoes {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-alterar,
        .btn-excluir {
            display: inline-block;
            padding: 7px 11px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-alterar {
            background: #e3f2fd;
            color: #1976D2;
        }

        .btn-alterar:hover {
            background: #bbdefb;
        }

        .btn-excluir {
            background: #ffebee;
            color: #c62828;
        }

        .btn-excluir:hover {
            background: #ffcdd2;
        }

        /* =====================================================
           MENSAGEM VAZIA
        ===================================================== */

        .mensagem-vazia {
            text-align: center;
            padding: 35px !important;
            color: #888 !important;
        }

        /* =====================================================
           RESPONSIVO
        ===================================================== */

        @media (max-width: 768px) {

            .container-consultas {
                width: 94%;
                margin: 25px auto;
            }

            .titulo-pagina h1 {
                font-size: 26px;
            }

            .card-consulta {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .grupo-completo {
                grid-column: auto;
            }

        }

    </style>

</head>

<body>

<?php include("php/menu_admin.php"); ?>


<div class="container-consultas">

    <!-- =====================================================
         TÍTULO
    ====================================================== -->

    <div class="titulo-pagina">

        <h1>📅 Consultas</h1>

        <p>
            Cadastre e gerencie as consultas do MedCare.
        </p>

    </div>


    <!-- =====================================================
         CADASTRAR CONSULTA
    ====================================================== -->

    <div class="card-consulta">

        <h2>Nova Consulta</h2>

        <form action="salvarConsulta.php" method="POST">

            <div class="form-grid">


                <!-- PACIENTE -->

                <div class="grupo">

                    <label for="paciente_nome">
                        Paciente
                    </label>

                    <input
                        type="text"
                        id="paciente_nome"
                        name="paciente_nome"
                        placeholder="Digite o nome do paciente"
                        required
                    >

                </div>


                <!-- MÉDICO -->

                <div class="grupo">

                    <label for="medico_id">
                        Médico
                    </label>

                    <select
                        id="medico_id"
                        name="medico_id"
                        required
                    >

                        <option value="">
                            Selecione um médico
                        </option>

                        <?php

                        if (
                            $resultadoMedicos &&
                            mysqli_num_rows($resultadoMedicos) > 0
                        ) {

                            while (
                                $medico =
                                mysqli_fetch_assoc($resultadoMedicos)
                            ) {

                        ?>

                            <option
                                value="<?php echo (int)$medico['id']; ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    $medico['nome'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );

                                if (!empty($medico['especialidade'])) {

                                    echo " - " .
                                        htmlspecialchars(
                                            $medico['especialidade'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );

                                }

                                ?>

                            </option>

                        <?php

                            }

                        } else {

                        ?>

                            <option value="">
                                Nenhum médico cadastrado
                            </option>

                        <?php

                        }

                        ?>

                    </select>

                </div>


                <!-- DATA -->

                <div class="grupo">

                    <label for="data_consulta">
                        Data da consulta
                    </label>

                    <input
                        type="date"
                        id="data_consulta"
                        name="data_consulta"
                        required
                    >

                </div>


                <!-- HORA -->

                <div class="grupo">

                    <label for="hora_consulta">
                        Horário
                    </label>

                    <input
                        type="time"
                        id="hora_consulta"
                        name="hora_consulta"
                        required
                    >

                </div>


                <!-- OBSERVAÇÃO -->

                <div class="grupo grupo-completo">

                    <label for="observacao">
                        Observação
                    </label>

                    <textarea
                        id="observacao"
                        name="observacao"
                        placeholder="Digite uma observação, se necessário..."
                    ></textarea>

                </div>

            </div>


            <div class="botao-form">

                <button type="submit">
                    📅 Cadastrar Consulta
                </button>

            </div>

        </form>

    </div>


    <!-- =====================================================
         CONSULTAS CADASTRADAS
    ====================================================== -->

    <div class="card-consulta">

        <h2>Consultas Cadastradas</h2>


        <div class="tabela-wrapper">

            <table class="tabela-consultas">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Paciente</th>

                        <th>Médico</th>

                        <th>Data</th>

                        <th>Horário</th>

                        <th>Status</th>

                        <th>Observação</th>

                        <th>Ações</th>

                    </tr>

                </thead>


                <tbody>

                <?php

                if (
                    $resultado &&
                    mysqli_num_rows($resultado) > 0
                ) {

                    while (
                        $consulta =
                        mysqli_fetch_assoc($resultado)
                    ) {

                        $status =
                            $consulta['status'] ?? 'agendada';


                        if ($status === 'realizada') {

                            $classeStatus =
                                'status-realizada';

                        } elseif ($status === 'cancelada') {

                            $classeStatus =
                                'status-cancelada';

                        } else {

                            $classeStatus =
                                'status-agendada';

                        }


                        if ($status === 'realizada') {

                            $textoStatus = 'Realizada';

                        } elseif ($status === 'cancelada') {

                            $textoStatus = 'Cancelada';

                        } else {

                            $textoStatus = 'Agendada';

                        }


                        $dataFormatada = '';

                        if (!empty($consulta['data_consulta'])) {

                            $dataFormatada =
                                date(
                                    'd/m/Y',
                                    strtotime(
                                        $consulta['data_consulta']
                                    )
                                );

                        }

                ?>

                    <tr>

                        <!-- ID -->

                        <td>

                            <?php
                            echo (int)$consulta['id'];
                            ?>

                        </td>


                        <!-- PACIENTE -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $consulta['paciente'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            ?>

                        </td>


                        <!-- MÉDICO -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $consulta['medico'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            ?>

                        </td>


                        <!-- DATA -->

                        <td>

                            <?php
                            echo htmlspecialchars(
                                $dataFormatada,
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>

                        </td>


                        <!-- HORA -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $consulta['hora_consulta'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            ?>

                        </td>


                        <!-- STATUS -->

                        <td>

                            <span
                                class="status <?php echo $classeStatus; ?>"
                            >

                                <?php
                                echo $textoStatus;
                                ?>

                            </span>

                        </td>


                        <!-- OBSERVAÇÃO -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $consulta['observacao'] ?? '-',
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            ?>

                        </td>


                        <!-- AÇÕES -->

                        <td>

                            <div class="acoes">

                                <a
                                    href="editarConsulta.php?id=<?php echo (int)$consulta['id']; ?>"
                                    class="btn-alterar"
                                >
                                    ✏️ Alterar
                                </a>


                                <a
                                    href="excluirConsulta.php?id=<?php echo (int)$consulta['id']; ?>"
                                    class="btn-excluir"
                                    onclick="return confirm('Tem certeza que deseja excluir esta consulta?');"
                                >
                                    🗑️ Excluir
                                </a>

                            </div>

                        </td>

                    </tr>

                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td
                            colspan="8"
                            class="mensagem-vazia"
                        >

                            📅 Nenhuma consulta cadastrada.

                        </td>

                    </tr>

                <?php

                }

                ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>

</html>