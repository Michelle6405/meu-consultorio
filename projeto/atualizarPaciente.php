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

    header("Location: pacientes.php");
    exit;

}


// ==========================================
// RECEBER DADOS
// ==========================================

$id = (int)($_POST['id'] ?? 0);

$nome = trim($_POST['nome'] ?? '');
$cpf = trim($_POST['cpf'] ?? '');
$rg = trim($_POST['rg'] ?? '');
$data_nascimento = $_POST['data_nascimento'] ?? '';
$sexo = $_POST['sexo'] ?? '';
$telefone = trim($_POST['telefone'] ?? '');
$email = trim($_POST['email'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$estado = trim($_POST['estado'] ?? '');


// ==========================================
// VALIDAR DADOS
// ==========================================

if (
    $id <= 0 ||
    empty($nome) ||
    empty($cpf)
) {

    echo "<script>

        alert('Preencha os campos obrigatórios: Nome e CPF.');

        window.location='pacientes.php';

    </script>";

    exit;

}


// ==========================================
// ATUALIZAR PACIENTE
// ==========================================

$sql = "
    UPDATE pacientes
    SET
        nome = ?,
        cpf = ?,
        rg = ?,
        data_nascimento = ?,
        sexo = ?,
        telefone = ?,
        email = ?,
        endereco = ?,
        cidade = ?,
        estado = ?
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

        alert('Erro ao preparar a alteração do paciente.');

        window.location='pacientes.php';

    </script>";

    exit;

}


// ==========================================
// ENVIAR VALORES
// ==========================================

mysqli_stmt_bind_param(
    $stmt,
    "ssssssssssi",
    $nome,
    $cpf,
    $rg,
    $data_nascimento,
    $sexo,
    $telefone,
    $email,
    $endereco,
    $cidade,
    $estado,
    $id
);


// ==========================================
// EXECUTAR
// ==========================================

if (mysqli_stmt_execute($stmt)) {

    echo "<script>

        alert('Paciente alterado com sucesso!');

        window.location='pacientes.php';

    </script>";

} else {

    echo "<script>

        alert('Erro ao alterar o paciente.');

        window.location='pacientes.php';

    </script>";

}


// ==========================================
// FECHAR
// ==========================================

mysqli_stmt_close($stmt);

mysqli_close($conexao);

?>