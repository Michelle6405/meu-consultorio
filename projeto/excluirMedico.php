<?php

// ==========================================
// SEGURANÇA E CONEXÃO
// ==========================================

include("php/protecao_admin.php");
include("php/conexao.php");


// ==========================================
// VERIFICAR ID
// ==========================================

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {

    echo "<script>

        alert('Médico não encontrado!');

        window.location='medicos.php';

    </script>";

    exit;
}


// ==========================================
// VERIFICAR SE O MÉDICO POSSUI CONSULTAS
// ==========================================

$sql = "
    SELECT COUNT(*) AS total
    FROM consultas
    WHERE medico_id = ?
";


$stmt = mysqli_prepare(
    $conexao,
    $sql
);


if (!$stmt) {

    echo "<script>

        alert('Erro ao verificar as consultas do médico.');

        window.location='medicos.php';

    </script>";

    exit;
}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);


mysqli_stmt_execute($stmt);


$resultado = mysqli_stmt_get_result($stmt);


$dados = mysqli_fetch_assoc($resultado);


mysqli_stmt_close($stmt);


// ==========================================
// SE POSSUI CONSULTAS, NÃO EXCLUI
// ==========================================

if ((int)$dados['total'] > 0) {

    mysqli_close($conexao);

    echo "<script>

        alert('Não é possível excluir este médico porque ele possui consultas cadastradas. Exclua ou altere as consultas antes de excluir o médico.');

        window.location='medicos.php';

    </script>";

    exit;
}


// ==========================================
// EXCLUIR MÉDICO
// ==========================================

$sql = "
    DELETE FROM medicos
    WHERE id = ?
";


$stmt = mysqli_prepare(
    $conexao,
    $sql
);


if (!$stmt) {

    echo "<script>

        alert('Erro ao preparar a exclusão do médico.');

        window.location='medicos.php';

    </script>";

    exit;
}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);


// ==========================================
// EXECUTAR EXCLUSÃO
// ==========================================

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    mysqli_close($conexao);

    echo "<script>

        alert('Médico excluído com sucesso!');

        window.location='medicos.php';

    </script>";

    exit;

} else {

    mysqli_stmt_close($stmt);

    mysqli_close($conexao);

    echo "<script>

        alert('Não foi possível excluir o médico.');

        window.location='medicos.php';

    </script>";

    exit;
}

?>