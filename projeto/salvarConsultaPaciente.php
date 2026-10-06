<?php

// =========================================================
// INICIAR SESSÃO
// =========================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// =========================================================
// CONEXÃO
// =========================================================

include("php/conexao.php");


// =========================================================
// VERIFICAR SE O FORMULÁRIO FOI ENVIADO
// =========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /projeto/agendar.php");
    exit;
}


// =========================================================
// PEGAR DADOS
// =========================================================

$nome = trim($_POST['nome'] ?? '');

$cpf = trim($_POST['cpf'] ?? '');

$telefone = trim($_POST['telefone'] ?? '');

$medico_id = (int) ($_POST['medico_id'] ?? 0);

$data_consulta = $_POST['data_consulta'] ?? '';

$hora_consulta = $_POST['hora_consulta'] ?? '';

$observacao = trim($_POST['observacao'] ?? '');


// =========================================================
// VALIDAR DADOS
// =========================================================

if (
    empty($nome) ||
    empty($cpf) ||
    empty($telefone) ||
    $medico_id <= 0 ||
    empty($data_consulta) ||
    empty($hora_consulta)
) {

    echo "
    <script>
        alert('Preencha todos os campos obrigatórios.');
        window.location.href = '/projeto/agendar.php';
    </script>
    ";

    exit;
}


// =========================================================
// VERIFICAR SE O MÉDICO EXISTE
// =========================================================

$sqlMedico = "
    SELECT id
    FROM medicos
    WHERE id = ?
    LIMIT 1
";

$stmtMedico = mysqli_prepare($conexao, $sqlMedico);

if (!$stmtMedico) {
    die("Erro ao verificar médico: " . mysqli_error($conexao));
}

mysqli_stmt_bind_param(
    $stmtMedico,
    "i",
    $medico_id
);

mysqli_stmt_execute($stmtMedico);

$resultadoMedico = mysqli_stmt_get_result($stmtMedico);

if (
    !$resultadoMedico ||
    mysqli_num_rows($resultadoMedico) === 0
) {

    mysqli_stmt_close($stmtMedico);

    echo "
    <script>
        alert('O médico selecionado não existe.');
        window.location.href = '/projeto/agendar.php';
    </script>
    ";

    exit;
}

mysqli_stmt_close($stmtMedico);


// =========================================================
// VERIFICAR SE O HORÁRIO JÁ ESTÁ OCUPADO
// =========================================================

$sqlConflito = "
    SELECT id
    FROM consultas
    WHERE medico_id = ?
      AND data_consulta = ?
      AND hora_consulta = ?
      AND status = 'agendada'
    LIMIT 1
";

$stmtConflito = mysqli_prepare($conexao, $sqlConflito);

if (!$stmtConflito) {
    die("Erro ao verificar horário: " . mysqli_error($conexao));
}

mysqli_stmt_bind_param(
    $stmtConflito,
    "iss",
    $medico_id,
    $data_consulta,
    $hora_consulta
);

mysqli_stmt_execute($stmtConflito);

$resultadoConflito = mysqli_stmt_get_result($stmtConflito);

if (
    $resultadoConflito &&
    mysqli_num_rows($resultadoConflito) > 0
) {

    mysqli_stmt_close($stmtConflito);

    echo "
    <script>
        alert('Este horário já está ocupado para este médico. Escolha outro horário.');
        window.location.href = '/projeto/agendar.php';
    </script>
    ";

    exit;
}

mysqli_stmt_close($stmtConflito);


// =========================================================
// PROCURAR PACIENTE PELO CPF
// =========================================================

$sqlPaciente = "
    SELECT id
    FROM pacientes
    WHERE cpf = ?
    LIMIT 1
";

$stmtPaciente = mysqli_prepare($conexao, $sqlPaciente);

if (!$stmtPaciente) {
    die("Erro ao procurar paciente: " . mysqli_error($conexao));
}

mysqli_stmt_bind_param(
    $stmtPaciente,
    "s",
    $cpf
);

mysqli_stmt_execute($stmtPaciente);

$resultadoPaciente = mysqli_stmt_get_result($stmtPaciente);


// =========================================================
// SE O PACIENTE JÁ EXISTE
// =========================================================

if (
    $resultadoPaciente &&
    mysqli_num_rows($resultadoPaciente) > 0
) {

    $paciente = mysqli_fetch_assoc($resultadoPaciente);

    $paciente_id = (int) $paciente['id'];

    mysqli_stmt_close($stmtPaciente);

}


// =========================================================
// SE O PACIENTE NÃO EXISTE
// =========================================================

else {

    mysqli_stmt_close($stmtPaciente);


    $sqlNovoPaciente = "
        INSERT INTO pacientes
        (
            nome,
            cpf,
            telefone
        )
        VALUES
        (?, ?, ?)
    ";

    $stmtNovoPaciente = mysqli_prepare(
        $conexao,
        $sqlNovoPaciente
    );

    if (!$stmtNovoPaciente) {

        die(
            "Erro ao cadastrar paciente: " .
            mysqli_error($conexao)
        );

    }


    mysqli_stmt_bind_param(
        $stmtNovoPaciente,
        "sss",
        $nome,
        $cpf,
        $telefone
    );


    if (!mysqli_stmt_execute($stmtNovoPaciente)) {

        $erro = mysqli_stmt_error(
            $stmtNovoPaciente
        );

        mysqli_stmt_close(
            $stmtNovoPaciente
        );

        echo "
        <script>
            alert('Erro ao cadastrar paciente: " .
            addslashes($erro) .
            "');
            window.location.href = '/projeto/agendar.php';
        </script>
        ";

        exit;

    }


    $paciente_id = mysqli_insert_id($conexao);

    mysqli_stmt_close(
        $stmtNovoPaciente
    );
}


// =========================================================
// SALVAR CONSULTA
// =========================================================

$sqlConsulta = "
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
    (?, ?, ?, ?, ?, 'agendada')
";

$stmtConsulta = mysqli_prepare(
    $conexao,
    $sqlConsulta
);

if (!$stmtConsulta) {

    die(
        "Erro ao preparar agendamento: " .
        mysqli_error($conexao)
    );

}


mysqli_stmt_bind_param(
    $stmtConsulta,
    "iisss",
    $paciente_id,
    $medico_id,
    $data_consulta,
    $hora_consulta,
    $observacao
);


// =========================================================
// EXECUTAR
// =========================================================

if (mysqli_stmt_execute($stmtConsulta)) {

    mysqli_stmt_close($stmtConsulta);

    echo "
    <script>

        alert(
            'Consulta agendada com sucesso!'
        );

        window.location.href =
            '/projeto/index.php';

    </script>
    ";

    exit;

}


// =========================================================
// ERRO
// =========================================================

$erro = mysqli_stmt_error(
    $stmtConsulta
);

mysqli_stmt_close(
    $stmtConsulta
);


echo "
<script>

    alert(
        'Erro ao agendar consulta: " .
        addslashes($erro) .
        "'
    );

    window.location.href =
        '/projeto/agendar.php';

</script>
";

exit;

?>