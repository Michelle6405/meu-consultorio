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

    header("Location: pacientes.php");
    exit;

}

$id = (int)$_GET['id'];


// ==========================================
// BUSCAR PACIENTE
// ==========================================

$sql = "
    SELECT *
    FROM pacientes
    WHERE id = ?
";

$stmt = mysqli_prepare(
    $conexao,
    $sql
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);


// ==========================================
// VERIFICAR SE O PACIENTE EXISTE
// ==========================================

if (mysqli_num_rows($resultado) == 0) {

    echo "<script>

        alert('Paciente não encontrado!');

        window.location='pacientes.php';

    </script>";

    exit;

}

$paciente = mysqli_fetch_assoc($resultado);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Editar Paciente - MedCare</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

    <style>

        .editar-paciente {
            max-width: 850px;
            margin: 30px auto;
        }

        .editar-paciente h2 {
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


<!-- ==========================================
     CONTEÚDO
========================================== -->

<div class="container editar-paciente">

    <h2>✏️ Editar Paciente</h2>


    <form
        action="atualizarPaciente.php"
        method="POST"
    >


        <!-- ID -->

        <input
            type="hidden"
            name="id"
            value="<?php echo (int)$paciente['id']; ?>"
        >


        <!-- NOME -->

        <div class="grupo">

            <label>
                Nome
            </label>

            <input
                type="text"
                name="nome"
                value="<?php echo htmlspecialchars(
                    $paciente['nome'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
                required
            >

        </div>


        <!-- CPF -->

        <div class="grupo">

            <label>
                CPF
            </label>

            <input
                type="text"
                name="cpf"
                value="<?php echo htmlspecialchars(
                    $paciente['cpf'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
                required
            >

        </div>


        <!-- RG -->

        <div class="grupo">

            <label>
                RG
            </label>

            <input
                type="text"
                name="rg"
                value="<?php echo htmlspecialchars(
                    $paciente['rg'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
            >

        </div>


        <!-- DATA DE NASCIMENTO -->

        <div class="grupo">

            <label>
                Data de Nascimento
            </label>

            <input
                type="date"
                name="data_nascimento"
                value="<?php echo htmlspecialchars(
                    $paciente['data_nascimento'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
            >

        </div>


        <!-- SEXO -->

        <div class="grupo">

            <label>
                Sexo
            </label>

            <select name="sexo">

                <option
                    value="Masculino"
                    <?php
                    if (
                        ($paciente['sexo'] ?? '') ===
                        'Masculino'
                    ) {
                        echo 'selected';
                    }
                    ?>
                >
                    Masculino
                </option>

                <option
                    value="Feminino"
                    <?php
                    if (
                        ($paciente['sexo'] ?? '') ===
                        'Feminino'
                    ) {
                        echo 'selected';
                    }
                    ?>
                >
                    Feminino
                </option>

                <option
                    value="Outro"
                    <?php
                    if (
                        ($paciente['sexo'] ?? '') ===
                        'Outro'
                    ) {
                        echo 'selected';
                    }
                    ?>
                >
                    Outro
                </option>

            </select>

        </div>


        <!-- TELEFONE -->

        <div class="grupo">

            <label>
                Telefone
            </label>

            <input
                type="text"
                name="telefone"
                value="<?php echo htmlspecialchars(
                    $paciente['telefone'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
            >

        </div>


        <!-- EMAIL -->

        <div class="grupo">

            <label>
                E-mail
            </label>

            <input
                type="email"
                name="email"
                value="<?php echo htmlspecialchars(
                    $paciente['email'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
            >

        </div>


        <!-- CIDADE -->

        <div class="grupo">

            <label>
                Cidade
            </label>

            <input
                type="text"
                name="cidade"
                value="<?php echo htmlspecialchars(
                    $paciente['cidade'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
            >

        </div>


        <!-- ENDEREÇO -->

        <div class="grupo campo-grande">

            <label>
                Endereço
            </label>

            <input
                type="text"
                name="endereco"
                value="<?php echo htmlspecialchars(
                    $paciente['endereco'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
            >

        </div>


        <!-- ESTADO -->

        <div class="grupo">

            <label>
                Estado
            </label>

            <input
                type="text"
                name="estado"
                value="<?php echo htmlspecialchars(
                    $paciente['estado'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
            >

        </div>


        <!-- BOTÕES -->

        <div class="botoes-edicao">

            <button type="submit">
                💾 Salvar Alterações
            </button>

            <button
                type="button"
                class="btn-cancelar"
                onclick="window.location.href='pacientes.php'"
            >
                Cancelar
            </button>

        </div>


    </form>

</div>


</body>

</html>