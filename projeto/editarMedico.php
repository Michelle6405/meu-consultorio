<?php

// ==========================================
// SEGURANÇA E CONEXÃO
// ==========================================

include("php/protecao_admin.php");
include("php/conexao.php");


// ==========================================
// VERIFICAR ID
// ==========================================

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {

    echo "<script>

        alert('Médico não encontrado!');

        window.location='medicos.php';

    </script>";

    exit;
}


// ==========================================
// SE FOR POST, ATUALIZAR MÉDICO
// ==========================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST['nome'] ?? '');

    $crm = trim($_POST['crm'] ?? '');

    $especialidade_id = (int)($_POST['especialidade_id'] ?? 0);

    $telefone = trim($_POST['telefone'] ?? '');

    $email = trim($_POST['email'] ?? '');


    // ==========================================
    // VALIDAR CAMPOS
    // ==========================================

    if (
        empty($nome) ||
        empty($crm) ||
        $especialidade_id <= 0
    ) {

        echo "<script>

            alert('Preencha todos os campos obrigatórios!');

            window.location='editarMedico.php?id=$id';

        </script>";

        exit;
    }


    // ==========================================
    // ATUALIZAR
    // ==========================================

    $sql = "
        UPDATE medicos
        SET
            nome = ?,
            crm = ?,
            especialidade_id = ?,
            telefone = ?,
            email = ?
        WHERE id = ?
    ";


    $stmt = mysqli_prepare(
        $conexao,
        $sql
    );


    if (!$stmt) {

        echo "<script>

            alert('Erro ao preparar a alteração do médico.');

            window.location='medicos.php';

        </script>";

        exit;
    }


    mysqli_stmt_bind_param(
        $stmt,
        "ssissi",
        $nome,
        $crm,
        $especialidade_id,
        $telefone,
        $email,
        $id
    );


    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);
        mysqli_close($conexao);

        echo "<script>

            alert('Médico alterado com sucesso!');

            window.location='medicos.php';

        </script>";

        exit;

    } else {

        mysqli_stmt_close($stmt);

        echo "<script>

            alert('Erro ao alterar o médico.');

            window.location='medicos.php';

        </script>";

        exit;
    }
}


// ==========================================
// BUSCAR MÉDICO
// ==========================================

$sql = "
    SELECT
        id,
        nome,
        crm,
        especialidade_id,
        telefone,
        email
    FROM medicos
    WHERE id = ?
";


$stmt = mysqli_prepare(
    $conexao,
    $sql
);


if (!$stmt) {

    die("Erro ao buscar o médico.");

}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);


mysqli_stmt_execute($stmt);


$resultado = mysqli_stmt_get_result($stmt);


$medico = mysqli_fetch_assoc($resultado);


mysqli_stmt_close($stmt);


// ==========================================
// VERIFICAR MÉDICO
// ==========================================

if (!$medico) {

    mysqli_close($conexao);

    echo "<script>

        alert('Médico não encontrado!');

        window.location='medicos.php';

    </script>";

    exit;
}


// ==========================================
// BUSCAR ESPECIALIDADES
// ==========================================

$sqlEspecialidades = "
    SELECT id, nome
    FROM especialidades
    ORDER BY nome ASC
";


$especialidades = mysqli_query(
    $conexao,
    $sqlEspecialidades
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

    <title>Alterar Médico - MedCare</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>

<body>


<?php include("php/menu_admin.php"); ?>


<div class="container">


    <!-- ======================================
         ALTERAÇÃO DO MÉDICO
    ======================================= -->

    <div class="card-form">

        <h2>Alterar Médico</h2>


        <form method="POST">

            <!-- ID DO MÉDICO -->

            <input
                type="hidden"
                name="id"
                value="<?php echo (int)$medico['id']; ?>"
            >


            <!-- NOME -->

            <div class="grupo">

                <label for="nome">
                    Nome
                </label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    value="<?php
                        echo htmlspecialchars(
                            $medico['nome'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        );
                    ?>"
                    required
                >

            </div>


            <!-- CRM -->

            <div class="grupo">

                <label for="crm">
                    CRM
                </label>

                <input
                    type="text"
                    id="crm"
                    name="crm"
                    value="<?php
                        echo htmlspecialchars(
                            $medico['crm'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        );
                    ?>"
                    required
                >

            </div>


            <!-- ESPECIALIDADE -->

            <div class="grupo">

                <label for="especialidade_id">
                    Especialidade
                </label>

                <select
                    id="especialidade_id"
                    name="especialidade_id"
                    required
                >

                    <option value="">
                        Selecione uma especialidade
                    </option>


                    <?php

                    if (
                        $especialidades &&
                        mysqli_num_rows($especialidades) > 0
                    ) {

                        while (
                            $esp =
                            mysqli_fetch_assoc($especialidades)
                        ) {

                    ?>

                        <option
                            value="<?php
                                echo (int)$esp['id'];
                            ?>"
                            <?php

                            if (
                                (int)$esp['id'] ===
                                (int)$medico['especialidade_id']
                            ) {

                                echo "selected";

                            }

                            ?>
                        >

                            <?php

                            echo htmlspecialchars(
                                $esp['nome'],
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


            <!-- TELEFONE -->

            <div class="grupo">

                <label for="telefone">
                    Telefone
                </label>

                <input
                    type="text"
                    id="telefone"
                    name="telefone"
                    value="<?php
                        echo htmlspecialchars(
                            $medico['telefone'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        );
                    ?>"
                >

            </div>


            <!-- E-MAIL -->

            <div class="grupo">

                <label for="email">
                    E-mail
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?php
                        echo htmlspecialchars(
                            $medico['email'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        );
                    ?>"
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
                    onclick="window.location.href='medicos.php'"
                >
                    ❌ Cancelar
                </button>

            </div>

        </form>

    </div>

</div>


</body>

</html>