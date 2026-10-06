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

    header("Location: medicos.php");
    exit;

}


// ==========================================
// RECEBER DADOS
// ==========================================

$nome = trim($_POST['nome'] ?? '');

$crm = trim($_POST['crm'] ?? '');

$especialidade_id = (int)($_POST['especialidade_id'] ?? 0);

$telefone = trim($_POST['telefone'] ?? '');

$email = trim($_POST['email'] ?? '');


// ==========================================
// VALIDAR CAMPOS OBRIGATÓRIOS
// ==========================================

if (
    empty($nome) ||
    empty($crm) ||
    $especialidade_id <= 0
) {

    echo "<script>

        alert('Preencha todos os campos obrigatórios!');

        window.location='medicos.php';

    </script>";

    exit;

}


// ==========================================
// CADASTRAR MÉDICO
// ==========================================

$sql = "
    INSERT INTO medicos
    (
        nome,
        crm,
        especialidade_id,
        telefone,
        email
    )
    VALUES (?, ?, ?, ?, ?)
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

        alert('Erro ao preparar o cadastro do médico.');

        window.location='medicos.php';

    </script>";

    exit;

}


// ==========================================
// ENVIAR DADOS
// ==========================================

mysqli_stmt_bind_param(
    $stmt,
    "ssiss",
    $nome,
    $crm,
    $especialidade_id,
    $telefone,
    $email
);


// ==========================================
// EXECUTAR
// ==========================================

if (mysqli_stmt_execute($stmt)) {

    echo "<script>

        alert('Médico cadastrado com sucesso!');

        window.location='medicos.php';

    </script>";

} else {

    echo "<script>

        alert('Erro ao cadastrar o médico.');

        window.location='medicos.php';

    </script>";

}


// ==========================================
// FECHAR
// ==========================================

mysqli_stmt_close($stmt);

mysqli_close($conexao);

?>