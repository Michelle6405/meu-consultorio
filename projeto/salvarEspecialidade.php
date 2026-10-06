<?php

// ==========================================
// CONEXÃO COM O BANCO
// ==========================================

include("php/conexao.php");


// ==========================================
// VERIFICAR SE O FORMULÁRIO FOI ENVIADO
// ==========================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: especialidades.php");
    exit;
}


// ==========================================
// RECEBER DADOS
// ==========================================

$nome = trim($_POST['nome'] ?? '');


// ==========================================
// VALIDAR CAMPO
// ==========================================

if (empty($nome)) {

    echo "<script>

        alert('Informe o nome da especialidade!');

        window.location='especialidades.php';

    </script>";

    exit;
}


// ==========================================
// VERIFICAR SE A ESPECIALIDADE JÁ EXISTE
// ==========================================

$sqlVerificar = "
    SELECT id
    FROM especialidades
    WHERE nome = ?
    LIMIT 1
";

$stmtVerificar = mysqli_prepare(
    $conexao,
    $sqlVerificar
);


if (!$stmtVerificar) {

    echo "<script>

        alert('Erro ao verificar a especialidade!');

        window.location='especialidades.php';

    </script>";

    exit;
}


mysqli_stmt_bind_param(
    $stmtVerificar,
    "s",
    $nome
);


mysqli_stmt_execute(
    $stmtVerificar
);


$resultado = mysqli_stmt_get_result(
    $stmtVerificar
);


// ==========================================
// IMPEDIR DUPLICIDADE
// ==========================================

if (mysqli_num_rows($resultado) > 0) {

    mysqli_stmt_close($stmtVerificar);
    mysqli_close($conexao);

    echo "<script>

        alert('Esta especialidade já está cadastrada!');

        window.location='especialidades.php';

    </script>";

    exit;
}


// ==========================================
// CADASTRAR ESPECIALIDADE
// ==========================================

$sql = "
    INSERT INTO especialidades (nome)
    VALUES (?)
";


$stmt = mysqli_prepare(
    $conexao,
    $sql
);


if (!$stmt) {

    mysqli_stmt_close($stmtVerificar);
    mysqli_close($conexao);

    echo "<script>

        alert('Erro ao preparar o cadastro da especialidade!');

        window.location='especialidades.php';

    </script>";

    exit;
}


mysqli_stmt_bind_param(
    $stmt,
    "s",
    $nome
);


// ==========================================
// EXECUTAR CADASTRO
// ==========================================

if (mysqli_stmt_execute($stmt)) {

    echo "<script>

        alert('Especialidade cadastrada com sucesso!');

        window.location='especialidades.php';

    </script>";

} else {

    echo "<script>

        alert('Erro ao cadastrar a especialidade: " .
        addslashes(mysqli_error($conexao)) .
        "');

        window.location='especialidades.php';

    </script>";
}


// ==========================================
// FECHAR
// ==========================================

mysqli_stmt_close($stmt);
mysqli_stmt_close($stmtVerificar);
mysqli_close($conexao);

?>