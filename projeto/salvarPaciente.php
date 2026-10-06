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

$nome = trim($_POST['nome'] ?? '');
$cpf = trim($_POST['cpf'] ?? '');
$rg = trim($_POST['rg'] ?? '');

$data_nascimento = $_POST['data_nascimento'] ?? '';

$sexo = trim($_POST['sexo'] ?? '');

$telefone = trim($_POST['telefone'] ?? '');
$email = trim($_POST['email'] ?? '');

$senha = $_POST['senha'] ?? '';

$endereco = trim($_POST['endereco'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$estado = trim($_POST['estado'] ?? '');


// ==========================================
// VALIDAR CAMPOS OBRIGATÓRIOS
// ==========================================

if (
    empty($nome) ||
    empty($cpf) ||
    empty($email) ||
    empty($senha)
) {

    echo "<script>

        alert('Preencha os campos obrigatórios: Nome, CPF, E-mail e Senha.');

        window.location='pacientes.php';

    </script>";

    exit;

}


// ==========================================
// VERIFICAR E-MAIL EXISTENTE
// ==========================================

$sqlEmail = "
    SELECT id
    FROM usuarios
    WHERE email = ?
    LIMIT 1
";

$stmtEmail = mysqli_prepare($conexao, $sqlEmail);

if (!$stmtEmail) {

    echo "<script>

        alert('Erro ao verificar o e-mail.');

        window.location='pacientes.php';

    </script>";

    exit;

}

mysqli_stmt_bind_param(
    $stmtEmail,
    "s",
    $email
);

mysqli_stmt_execute($stmtEmail);

$resultadoEmail = mysqli_stmt_get_result($stmtEmail);

if (mysqli_num_rows($resultadoEmail) > 0) {

    mysqli_stmt_close($stmtEmail);

    echo "<script>

        alert('Este e-mail já está cadastrado no sistema.');

        window.location='pacientes.php';

    </script>";

    exit;

}

mysqli_stmt_close($stmtEmail);


// ==========================================
// VERIFICAR CPF EXISTENTE
// ==========================================

$sqlCpf = "
    SELECT id
    FROM pacientes
    WHERE cpf = ?
    LIMIT 1
";

$stmtCpf = mysqli_prepare($conexao, $sqlCpf);

if (!$stmtCpf) {

    echo "<script>

        alert('Erro ao verificar o CPF.');

        window.location='pacientes.php';

    </script>";

    exit;

}

mysqli_stmt_bind_param(
    $stmtCpf,
    "s",
    $cpf
);

mysqli_stmt_execute($stmtCpf);

$resultadoCpf = mysqli_stmt_get_result($stmtCpf);

if (mysqli_num_rows($resultadoCpf) > 0) {

    mysqli_stmt_close($stmtCpf);

    echo "<script>

        alert('Este CPF já está cadastrado.');

        window.location='pacientes.php';

    </script>";

    exit;

}

mysqli_stmt_close($stmtCpf);


// ==========================================
// CRIPTOGRAFAR SENHA
// ==========================================

$senhaCriptografada = password_hash(
    $senha,
    PASSWORD_DEFAULT
);


// ==========================================
// INICIAR TRANSAÇÃO
// ==========================================

mysqli_begin_transaction($conexao);

try {


    // ==========================================
    // CRIAR USUÁRIO DO PACIENTE
    // ==========================================

    $sqlUsuario = "
        INSERT INTO usuarios
        (
            nome,
            email,
            senha,
            tipo,
            ativo
        )
        VALUES
        (
            ?,
            ?,
            ?,
            'paciente',
            1
        )
    ";

    $stmtUsuario = mysqli_prepare(
        $conexao,
        $sqlUsuario
    );

    if (!$stmtUsuario) {

        throw new Exception(
            "Erro ao preparar o usuário."
        );

    }

    mysqli_stmt_bind_param(
        $stmtUsuario,
        "sss",
        $nome,
        $email,
        $senhaCriptografada
    );

    if (!mysqli_stmt_execute($stmtUsuario)) {

        throw new Exception(
            "Erro ao criar o usuário."
        );

    }


    // ==========================================
    // PEGAR ID DO USUÁRIO CRIADO
    // ==========================================

    $usuario_id = mysqli_insert_id($conexao);


    mysqli_stmt_close($stmtUsuario);


    // ==========================================
    // CADASTRAR PACIENTE
    // ==========================================

    $sqlPaciente = "
        INSERT INTO pacientes
        (
            usuario_id,
            nome,
            cpf,
            rg,
            data_nascimento,
            sexo,
            telefone,
            email,
            endereco,
            cidade,
            estado
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?
        )
    ";

    $stmtPaciente = mysqli_prepare(
        $conexao,
        $sqlPaciente
    );

    if (!$stmtPaciente) {

        throw new Exception(
            "Erro ao preparar o paciente."
        );

    }


    // ==========================================
    // TRATAR DATA VAZIA
    // ==========================================

    if ($data_nascimento === '') {
        $data_nascimento = null;
    }


    // ==========================================
    // INSERIR PACIENTE
    // ==========================================

    mysqli_stmt_bind_param(
        $stmtPaciente,
        "issssssssss",
        $usuario_id,
        $nome,
        $cpf,
        $rg,
        $data_nascimento,
        $sexo,
        $telefone,
        $email,
        $endereco,
        $cidade,
        $estado
    );


    if (!mysqli_stmt_execute($stmtPaciente)) {

        throw new Exception(
            "Erro ao cadastrar o paciente."
        );

    }


    mysqli_stmt_close($stmtPaciente);


    // ==========================================
    // CONFIRMAR TUDO
    // ==========================================

    mysqli_commit($conexao);


    // ==========================================
    // SUCESSO
    // ==========================================

    echo "<script>

        alert('Paciente cadastrado com sucesso! O acesso para login foi criado.');

        window.location='pacientes.php';

    </script>";

    exit;


} catch (Exception $erro) {


    // ==========================================
    // DESFAZER ALTERAÇÕES
    // ==========================================

    mysqli_rollback($conexao);


    echo "<script>

        alert('Erro ao cadastrar o paciente.');

        window.location='pacientes.php';

    </script>";

    exit;

}


// ==========================================
// FECHAR CONEXÃO
// ==========================================

mysqli_close($conexao);

?>