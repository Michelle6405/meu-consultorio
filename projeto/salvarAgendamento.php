<?php

// ==========================================
// PROTEÇÃO DO PACIENTE
// ==========================================

include("php/protecao_paciente.php");
include("php/conexao.php");


// ==========================================
// VERIFICAR MÉTODO
// ==========================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: paciente.php");
    exit;

}


// ==========================================
// PEGAR USUÁRIO LOGADO
// ==========================================

$usuario_id = $_SESSION['usuario_id'];


// ==========================================
// RECEBER DADOS
// ==========================================

$medico_id = intval($_POST['medico_id'] ?? 0);

$data_consulta = $_POST['data_consulta'] ?? '';

$hora_consulta = $_POST['hora_consulta'] ?? '';

$observacao = trim($_POST['observacao'] ?? '');


// ==========================================
// VERIFICAR CAMPOS
// ==========================================

if (
    $medico_id <= 0 ||
    empty($data_consulta) ||
    empty($hora_consulta)
) {

    echo "<script>

        alert('Preencha todos os campos obrigatórios.');

        window.location='agendar.php';

    </script>";

    exit;

}


// ==========================================
// VERIFICAR DATA
// ==========================================

if ($data_consulta < date('Y-m-d')) {

    echo "<script>

        alert('Não é possível agendar uma consulta para uma data que já passou.');

        window.location='agendar.php';

    </script>";

    exit;

}


// ==========================================
// BUSCAR PACIENTE DO USUÁRIO
// ==========================================

$sqlPaciente = "
    SELECT id
    FROM pacientes
    WHERE usuario_id = ?
    LIMIT 1
";

$stmtPaciente = mysqli_prepare(
    $conexao,
    $sqlPaciente
);

if (!$stmtPaciente) {

    die("Erro ao buscar o paciente.");

}

mysqli_stmt_bind_param(
    $stmtPaciente,
    "i",
    $usuario_id
);

mysqli_stmt_execute($stmtPaciente);

$resultadoPaciente = mysqli_stmt_get_result(
    $stmtPaciente
);

$paciente = mysqli_fetch_assoc(
    $resultadoPaciente
);

mysqli_stmt_close($stmtPaciente);


// ==========================================
// VERIFICAR PACIENTE
// ==========================================

if (!$paciente) {

    echo "<script>

        alert('Paciente não encontrado.');

        window.location='paciente.php';

    </script>";

    exit;

}

$paciente_id = $paciente['id'];


// ==========================================
// VERIFICAR MÉDICO
// ==========================================

$sqlMedico = "
    SELECT id
    FROM medicos
    WHERE id = ?
    LIMIT 1
";

$stmtMedico = mysqli_prepare(
    $conexao,
    $sqlMedico
);

if (!$stmtMedico) {

    die("Erro ao verificar o médico.");

}

mysqli_stmt_bind_param(
    $stmtMedico,
    "i",
    $medico_id
);

mysqli_stmt_execute($stmtMedico);

$resultadoMedico = mysqli_stmt_get_result(
    $stmtMedico
);

if (mysqli_num_rows($resultadoMedico) === 0) {

    mysqli_stmt_close($stmtMedico);

    echo "<script>

        alert('Médico não encontrado.');

        window.location='agendar.php';

    </script>";

    exit;

}

mysqli_stmt_close($stmtMedico);


// ==========================================
// VERIFICAR HORÁRIO OCUPADO
// ==========================================

$sqlHorario = "
    SELECT id
    FROM consultas
    WHERE medico_id = ?
    AND data_consulta = ?
    AND hora_consulta = ?
    AND status = 'agendada'
    LIMIT 1
";

$stmtHorario = mysqli_prepare(
    $conexao,
    $sqlHorario
);

if (!$stmtHorario) {

    die("Erro ao verificar o horário.");

}

mysqli_stmt_bind_param(
    $stmtHorario,
    "iss",
    $medico_id,
    $data_consulta,
    $hora_consulta
);

mysqli_stmt_execute($stmtHorario);

$resultadoHorario = mysqli_stmt_get_result(
    $stmtHorario
);

if (mysqli_num_rows($resultadoHorario) > 0) {

    mysqli_stmt_close($stmtHorario);

    echo "<script>

        alert('Este horário já está ocupado para este médico. Escolha outro horário.');

        window.location='agendar.php';

    </script>";

    exit;

}

mysqli_stmt_close($stmtHorario);


// ==========================================
// VERIFICAR SE O PACIENTE JÁ TEM CONSULTA
// NO MESMO HORÁRIO
// ==========================================

$sqlPacienteHorario = "
    SELECT id
    FROM consultas
    WHERE paciente_id = ?
    AND data_consulta = ?
    AND hora_consulta = ?
    AND status = 'agendada'
    LIMIT 1
";

$stmtPacienteHorario = mysqli_prepare(
    $conexao,
    $sqlPacienteHorario
);

if (!$stmtPacienteHorario) {

    die("Erro ao verificar suas consultas.");

}

mysqli_stmt_bind_param(
    $stmtPacienteHorario,
    "iss",
    $paciente_id,
    $data_consulta,
    $hora_consulta
);

mysqli_stmt_execute(
    $stmtPacienteHorario
);

$resultadoPacienteHorario = mysqli_stmt_get_result(
    $stmtPacienteHorario
);

if (mysqli_num_rows($resultadoPacienteHorario) > 0) {

    mysqli_stmt_close($stmtPacienteHorario);

    echo "<script>

        alert('Você já possui uma consulta agendada neste mesmo dia e horário.');

        window.location='agendar.php';

    </script>";

    exit;

}

mysqli_stmt_close($stmtPacienteHorario);


// ==========================================
// SALVAR CONSULTA
// ==========================================

$sql = "
    INSERT INTO consultas
    (
        paciente_id,
        medico_id,
        data_consulta,
        hora_consulta,
        observacao,
        status
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        ?,
        'agendada'
    )
";

$stmt = mysqli_prepare(
    $conexao,
    $sql
);

if (!$stmt) {

    echo "<script>

        alert('Erro ao preparar o agendamento.');

        window.location='agendar.php';

    </script>";

    exit;

}


// ==========================================
// ENVIAR DADOS
// ==========================================

mysqli_stmt_bind_param(
    $stmt,
    "iisss",
    $paciente_id,
    $medico_id,
    $data_consulta,
    $hora_consulta,
    $observacao
);


// ==========================================
// EXECUTAR
// ==========================================

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);
    mysqli_close($conexao);

    echo "<script>

        alert('Consulta agendada com sucesso!');

        window.location='minhas_consultas.php';

    </script>";

    exit;

}


// ==========================================
// ERRO
// ==========================================

mysqli_stmt_close($stmt);
mysqli_close($conexao);

echo "<script>

    alert('Erro ao realizar o agendamento.');

    window.location='agendar.php';

</script>";

exit;

?>