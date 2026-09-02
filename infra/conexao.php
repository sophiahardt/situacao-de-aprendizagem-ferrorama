<?php
$host = "localhost";
$usuario = "root";
$senha = "root";
$banco = "db_ferrovia";

$conexao = mysqli_connect($host, $usuario, $senha, $banco);
if ($conexao->connect_error) {
    die("Falha na conexão: " . ($conexao->connect_error));
}

$conexao->set_charset("utf8mb4");
?>