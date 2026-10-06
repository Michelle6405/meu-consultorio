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
// VERIFICAR MÉTODO
// =========================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: login.php");

    exit;
}


// =========================================================
// PEGAR DADOS DO FORMULÁRIO
// =========================================================

$email = trim($_POST['email'] ?? '');

$senha = $_POST['senha'] ?? '';


// =========================================================
// VERIFICAR CAMPOS
// =========================================================

if (
    empty($email) ||
    empty($senha)
) {

    echo "
        <script>
            alert('Preencha o e-mail e a senha.');
            window.location='login.php';
        </script>
    ";

    exit;
}


// =========================================================
// BUSCAR USUÁRIO
// =========================================================

$sql = "
    SELECT
        id,
        nome,
        email,
        senha,
        tipo,
        ativo
    FROM usuarios
    WHERE email = ?
    LIMIT 1
";


$stmt = mysqli_prepare(
    $conexao,
    $sql
);


if (!$stmt) {

    die(
        "Erro ao preparar login: " .
        mysqli_error($conexao)
    );

}


mysqli_stmt_bind_param(
    $stmt,
    "s",
    $email
);


mysqli_stmt_execute($stmt);


$resultado = mysqli_stmt_get_result($stmt);


if (
    !$resultado ||
    mysqli_num_rows($resultado) === 0
) {

    mysqli_stmt_close($stmt);

    echo "
        <script>
            alert('E-mail ou senha incorretos.');
            window.location='login.php';
        </script>
    ";

    exit;
}


$usuario = mysqli_fetch_assoc($resultado);


mysqli_stmt_close($stmt);


// =========================================================
// VERIFICAR CONTA ATIVA
// =========================================================

if ((int)$usuario['ativo'] !== 1) {

    echo "
        <script>
            alert('Esta conta está desativada.');
            window.location='login.php';
        </script>
    ";

    exit;
}


// =========================================================
// VERIFICAR SENHA
// =========================================================

if (
    !password_verify(
        $senha,
        $usuario['senha']
    )
) {

    echo "
        <script>
            alert('E-mail ou senha incorretos.');
            window.location='login.php';
        </script>
    ";

    exit;
}


// =========================================================
// CRIAR SESSÃO
// =========================================================

session_regenerate_id(true);


$_SESSION['usuario_id'] =
    (int)$usuario['id'];

$_SESSION['usuario_nome'] =
    $usuario['nome'];

$_SESSION['usuario_tipo'] =
    $usuario['tipo'];


// =========================================================
// REDIRECIONAMENTO
// =========================================================

// ADMIN
if ($usuario['tipo'] === 'admin') {

    header("Location: /projeto/dashboard.php");

    exit;
}


// PACIENTE
if ($usuario['tipo'] === 'paciente') {

    $destino =
        $_SESSION['pagina_destino'] ?? 'paciente.php';


    unset(
        $_SESSION['pagina_destino']
    );


    if ($destino === 'agendar.php') {

        header("Location: /projeto/agendar.php");

        exit;
    }


    header("Location: /projeto/paciente.php");

    exit;
}


// =========================================================
// TIPO INVÁLIDO
// =========================================================

session_unset();

session_destroy();


echo "
    <script>
        alert('Tipo de usuário inválido.');
        window.location='/projeto/login.php';
    </script>
";

exit;

?>