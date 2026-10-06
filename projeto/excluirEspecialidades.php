<?php

include("php/conexao.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {

    echo "<script>
        alert('Especialidade não encontrada!');
        window.location='especialidades.php';
    </script>";

    exit;
}

$id = intval($_GET['id']);

$sql = "DELETE FROM especialidades WHERE id = ?";

$stmt = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {

    echo "<script>
        alert('Especialidade excluída com sucesso!');
        window.location='especialidades.php';
    </script>";

} else {

    echo "<script>
        alert('Não foi possível excluir esta especialidade. 
        Verifique se ela está sendo utilizada por algum médico.');
        window.location='especialidades.php';
    </script>";
}

mysqli_stmt_close($stmt);

mysqli_close($conexao);

?>