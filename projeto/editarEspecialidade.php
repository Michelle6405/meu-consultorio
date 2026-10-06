<?php

include("php/conexao.php");

if (!isset($_GET['id'])) {
    header("Location: especialidades.php");
    exit;
}

$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST['nome']);

    if (!empty($nome)) {

        $sql = "UPDATE especialidades
                SET nome = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($conexao, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $nome,
            $id
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        header("Location: especialidades.php");
        exit;
    }
}

$sql = "SELECT * FROM especialidades WHERE id = ?";

$stmt = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

$especialidade = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

if (!$especialidade) {
    die("Especialidade não encontrada.");
}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Alterar Especialidade</title>

<link rel="stylesheet" href="css/style.css">

</head>

<body>

<header>

<div class="logo">

<h1>🏥 MedCare</h1>

</div>

<nav>

<ul>

<li><a href="index.php">Início</a></li>
<li><a href="pacientes.php">Pacientes</a></li>
<li><a href="medicos.php">Médicos</a></li>
<li><a href="consultas.php">Consultas</a></li>
<li><a href="especialidades.php">Especialidades</a></li>
<li><a href="sobre.php">Sobre</a></li>
<li><a href="contato.php">Contato</a></li>

</ul>

</nav>

</header>

<div class="container">

<h2>Alterar Especialidade</h2>

<form method="POST">

<label>Nome da Especialidade</label>

<input
type="text"
name="nome"
value="<?php echo htmlspecialchars($especialidade['nome']); ?>"
required
>

<br><br>

<button type="submit">
Salvar Alterações
</button>

<a href="especialidades.php">
Cancelar
</a>

</form>

</div>

</body>

</html>