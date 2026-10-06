<?php

// ==========================================
// SEGURANÇA E CONEXÃO
// ==========================================

include("php/protecao_admin.php");
include("php/conexao.php");


// ==========================================
// VERIFICAR ID
// ==========================================

if (!isset($_GET['id'])) {

    header("Location: consultas.php");
    exit;

}

$id = (int)$_GET['id'];


// ==========================================
// BUSCAR CONSULTA
// ==========================================

$sql = "
    SELECT *
    FROM consultas
    WHERE id = ?
";

$stmt = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);


// ==========================================
// VERIFICAR SE EXISTE
// ==========================================

if (mysqli_num_rows($resultado) == 0) {

    echo "Consulta não encontrada.";
    exit;

}

$consulta = mysqli_fetch_assoc($resultado);


// ==========================================
// BUSCAR PACIENTES
// ==========================================

$sqlPacientes = "
    SELECT id, nome
    FROM pacientes
    ORDER BY nome ASC
";

$resultadoPacientes = mysqli_query(
    $conexao,
    $sqlPacientes
);


// ==========================================
// BUSCAR MÉDICOS
// ==========================================

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

$resultadoMedicos = mysqli_query(
    $conexao,
    $sqlMedicos
);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Editar Consulta - MedCare</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

    <style>

        .editar-consulta {
            max-width: 850px;
            margin: 30px auto;
        }

        .editar-consulta h2 {
            color: #1976D2;
            margin-bottom: 25px;
        }

        .botoes-edicao {
            grid-column: 1 / 3;
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 10px;
        }

        .btn-cancelar {
            background: #6c757d;
            color: white;
            border: none;
            padding: 11px 20px;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
        }

        .btn-cancelar:hover {
            background: #545b62;
        }

        @media (max-width: 900px) {

            .botoes-edicao {
                grid-column: auto;
            }

        }

    </style>

</head>

<body>


<!-- ==========================================
     MENU ADMINISTRATIVO
========================================== -->

<?php include("php/menu_admin.php"); ?>


<div class="container editar-consulta">


    <h2>✏️ Editar Consulta</h2>


    <form
        action="atualizarConsulta.php"
        method="POST"
    >


        <!-- ID DA CONSULTA -->

        <input
            type="hidden"
            name="id"
            value="<?php echo (int)$consulta['id']; ?>"
        >


        <!-- PACIENTE -->

        <div class="grupo">

            <label>
                Paciente
            </label>

            <select
                name="paciente_id"
                required
            >

                <option value="">
                    Selecione um paciente
                </option>


                <?php

                if (
                    $resultadoPacientes &&
                    mysqli_num_rows($resultadoPacientes) > 0
                ) {

                    while (
                        $paciente =
                        mysqli_fetch_assoc(
                            $resultadoPacientes
                        )
                    ) {

                ?>

                    <option
                        value="<?php echo (int)$paciente['id']; ?>"
                        <?php

                        if (
                            (int)$paciente['id'] ===
                            (int)$consulta['paciente_id']
                        ) {

                            echo "selected";

                        }

                        ?>
                    >

                        <?php

                        echo htmlspecialchars(
                            $paciente['nome'],
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        ?>

                    </option>

                <?php

                    }

                }

                ?>

            </select>

        </div>


        <!-- MÉDICO -->

        <div class="grupo">

            <label>
                Médico
            </label>

            <select
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
                        mysqli_fetch_assoc(
                            $resultadoMedicos
                        )
                    ) {

                ?>

                    <option
                        value="<?php echo (int)$medico['id']; ?>"
                        <?php

                        if (
                            (int)$medico['id'] ===
                            (int)$consulta['medico_id']
                        ) {

                            echo "selected";

                        }

                        ?>
                    >

                        <?php

                        echo htmlspecialchars(
                            $medico['nome'],
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        if (
                            !empty(
                                $medico['especialidade']
                            )
                        ) {

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

                }

                ?>

            </select>

        </div>


        <!-- DATA -->

        <div class="grupo">

            <label>
                Data da Consulta
            </label>

            <input
                type="date"
                name="data_consulta"
                value="<?php echo htmlspecialchars(
                    $consulta['data_consulta'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
                required
            >

        </div>


        <!-- HORÁRIO -->

        <div class="grupo">

            <label>
                Horário
            </label>

            <input
                type="time"
                name="hora_consulta"
                value="<?php echo htmlspecialchars(
                    substr(
                        $consulta['hora_consulta'] ?? '',
                        0,
                        5
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
                required
            >

        </div>


        <!-- STATUS -->

        <div class="grupo">

            <label>
                Status da Consulta
            </label>

            <select
                name="status"
                required
            >

                <option
                    value="agendada"
                    <?php
                    if (
                        ($consulta['status'] ?? '') ===
                        'agendada'
                    ) {
                        echo "selected";
                    }
                    ?>
                >
                    Agendada
                </option>

                <option
                    value="realizada"
                    <?php
                    if (
                        ($consulta['status'] ?? '') ===
                        'realizada'
                    ) {
                        echo "selected";
                    }
                    ?>
                >
                    Realizada
                </option>

                <option
                    value="cancelada"
                    <?php
                    if (
                        ($consulta['status'] ?? '') ===
                        'cancelada'
                    ) {
                        echo "selected";
                    }
                    ?>
                >
                    Cancelada
                </option>

            </select>

        </div>


        <!-- OBSERVAÇÃO -->

        <div class="grupo campo-grande">

            <label>
                Observação
            </label>

            <textarea
                name="observacao"
                rows="5"
                placeholder="Observações da consulta"
            ><?php

            echo htmlspecialchars(
                $consulta['observacao'] ?? '',
                ENT_QUOTES,
                'UTF-8'
            );

            ?></textarea>

        </div>


        <!-- BOTÕES -->

        <div class="botoes-edicao">

            <button type="submit">
                💾 Salvar Alterações
            </button>

            <button
                type="button"
                class="btn-cancelar"
                onclick="window.location.href='consultas.php'"
            >
                Cancelar
            </button>

        </div>


    </form>

</div>


</body>

</html>