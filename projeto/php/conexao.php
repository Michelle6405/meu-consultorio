<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "consultorio";

$conexao = new mysqli(
    $host,
    $usuario,
    $senha,
    $banco
);

if ($conexao->connect_error) {
    die("Erro de conexão com o banco de dados.");
}

$conexao->set_charset("utf8mb4");

?>
