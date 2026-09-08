<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "db_ferrovia";
$porta = 3311;

$conexao = mysqli_connect($host, $usuario, $senha, $banco, $porta);
if ($conexao->connect_error) {
    die("Falha na conexão: " . ($conexao->connect_error));
}

$conexao->set_charset("utf8mb4");
?>