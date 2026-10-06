<?php

include("php/conexao.php");

$paciente_nome = trim($_POST['paciente_nome']);
$medico_id = $_POST['medico_id'];
$data_consulta = $_POST['data_consulta'];
$hora_consulta = $_POST['hora_consulta'];
$observacao = $_POST['observacao'];


// =====================================
// PROCURAR O PACIENTE PELO NOME
// =====================================

$sqlPaciente = "SELECT id FROM pacientes WHERE nome = ?";

$stmtPaciente = mysqli_prepare($conexao, $sqlPaciente);

mysqli_stmt_bind_param(
    $stmtPaciente,
    "s",
    $paciente_nome
);

mysqli_stmt_execute($stmtPaciente);

$resultadoPaciente = mysqli_stmt_get_result($stmtPaciente);


// =====================================
// VERIFICAR SE O PACIENTE EXISTE
// =====================================

if (mysqli_num_rows($resultadoPaciente) == 0) {

    echo "<script>
        alert('Paciente não encontrado! Verifique o nome digitado.');
        window.location='consultas.php';
    </script>";

    exit;
}


// =====================================
// PEGAR O ID DO PACIENTE
// =====================================

$paciente = mysqli_fetch_assoc($resultadoPaciente);

$paciente_id = $paciente['id'];


// =====================================
// SALVAR A CONSULTA
// =====================================

$sql = "INSERT INTO consultas
(
    paciente_id,
    medico_id,
    data_consulta,
    hora_consulta,
    observacao
)
VALUES
(
    ?,
    ?,
    ?,
    ?,
    ?
)";


$stmt = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "iisss",
    $paciente_id,
    $medico_id,
    $data_consulta,
    $hora_consulta,
    $observacao
);


// =====================================
// VERIFICAR O SALVAMENTO
// =====================================

if (mysqli_stmt_execute($stmt))
{
    echo "<script>
        alert('Consulta agendada com sucesso!');
        window.location='consultas.php';
    </script>";
}
else
{
    echo "<script>
        alert('Erro ao agendar a consulta!');
        window.location='consultas.php';
    </script>";
}


mysqli_close($conexao);

?>