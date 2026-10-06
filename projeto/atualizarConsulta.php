<?php

// ==========================================
// SEGURANÇA E CONEXÃO
// ==========================================

include("php/protecao_admin.php");
include("php/conexao.php");


// ==========================================
// VERIFICAR MÉTODO
// ==========================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: consultas.php");
    exit;

}


// ==========================================
// RECEBER DADOS
// ==========================================

$id = (int)($_POST['id'] ?? 0);

$paciente_id = (int)($_POST['paciente_id'] ?? 0);

$medico_id = (int)($_POST['medico_id'] ?? 0);

$data_consulta = $_POST['data_consulta'] ?? '';

$hora_consulta = $_POST['hora_consulta'] ?? '';

$observacao = trim($_POST['observacao'] ?? '');

$status = $_POST['status'] ?? 'agendada';


// ==========================================
// VALIDAR STATUS
// ==========================================

$statusPermitidos = [
    'agendada',
    'realizada',
    'cancelada'
];

if (!in_array($status, $statusPermitidos, true)) {

    $status = 'agendada';

}


// ==========================================
// VALIDAR CAMPOS
// ==========================================

if (
    $id <= 0 ||
    $paciente_id <= 0 ||
    $medico_id <= 0 ||
    empty($data_consulta) ||
    empty($hora_consulta)
) {

    echo "<script>

        alert('Preencha todos os campos obrigatórios.');

        window.location='consultas.php';

    </script>";

    exit;

}


// ==========================================
// ATUALIZAR CONSULTA
// ==========================================

$sql = "
    UPDATE consultas
    SET
        paciente_id = ?,
        medico_id = ?,
        data_consulta = ?,
        hora_consulta = ?,
        observacao = ?,
        status = ?
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

        alert('Erro ao preparar a atualização.');

        window.location='consultas.php';

    </script>";

    exit;

}


// ==========================================
// ENVIAR VALORES
// ==========================================

mysqli_stmt_bind_param(
    $stmt,
    "iissssi",
    $paciente_id,
    $medico_id,
    $data_consulta,
    $hora_consulta,
    $observacao,
    $status,
    $id
);


// ==========================================
// EXECUTAR
// ==========================================

if (mysqli_stmt_execute($stmt)) {

    echo "<script>

        alert('Consulta atualizada com sucesso!');

        window.location='consultas.php';

    </script>";

} else {

    echo "<script>

        alert('Erro ao atualizar a consulta.');

        window.location='consultas.php';

    </script>";

}


// ==========================================
// FECHAR
// ==========================================

mysqli_stmt_close($stmt);

mysqli_close($conexao);

?>