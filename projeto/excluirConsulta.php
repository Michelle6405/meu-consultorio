<?php

// ==========================================
// SEGURANÇA E CONEXÃO
// ==========================================

include("php/protecao_admin.php");
include("php/conexao.php");


// ==========================================
// VERIFICAR SE RECEBEU O ID
// ==========================================

if (!isset($_GET['id'])) {

    header("Location: consultas.php");
    exit;

}


$id = (int)$_GET['id'];


// ==========================================
// VALIDAR ID
// ==========================================

if ($id <= 0) {

    echo "<script>

        alert('Consulta inválida.');

        window.location='consultas.php';

    </script>";

    exit;

}


// ==========================================
// EXCLUIR CONSULTA
// ==========================================

$sql = "
    DELETE FROM consultas
    WHERE id = ?
";


$stmt = mysqli_prepare(
    $conexao,
    $sql
);


// ==========================================
// VERIFICAR PREPARAÇÃO
// ==========================================

if (!$stmt) {

    echo "<script>

        alert('Erro ao preparar a exclusão.');

        window.location='consultas.php';

    </script>";

    exit;

}


// ==========================================
// ENVIAR ID
// ==========================================

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);


// ==========================================
// EXECUTAR
// ==========================================

if (mysqli_stmt_execute($stmt)) {

    echo "<script>

        alert('Consulta excluída com sucesso!');

        window.location='consultas.php';

    </script>";

} else {

    echo "<script>

        alert('Erro ao excluir a consulta.');

        window.location='consultas.php';

    </script>";

}


// ==========================================
// FECHAR
// ==========================================

mysqli_stmt_close($stmt);

mysqli_close($conexao);

?>