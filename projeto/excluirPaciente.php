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

    echo "<script>

        alert('Paciente não encontrado!');

        window.location='pacientes.php';

    </script>";

    exit;

}


$id = (int)$_GET['id'];


// ==========================================
// VALIDAR ID
// ==========================================

if ($id <= 0) {

    echo "<script>

        alert('Paciente inválido!');

        window.location='pacientes.php';

    </script>";

    exit;

}


// ==========================================
// EXCLUIR PACIENTE
// ==========================================

$sql = "
    DELETE FROM pacientes
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

        alert('Erro ao preparar a exclusão do paciente.');

        window.location='pacientes.php';

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

        alert('Paciente excluído com sucesso!');

        window.location='pacientes.php';

    </script>";

} else {

    echo "<script>

        alert('Não foi possível excluir o paciente. Ele pode estar vinculado a uma consulta.');

        window.location='pacientes.php';

    </script>";

}


// ==========================================
// FECHAR
// ==========================================

mysqli_stmt_close($stmt);

mysqli_close($conexao);

?>