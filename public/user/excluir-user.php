<?php
session_start();
require_once "../../infra/protecao.php";
verificarAdministrador();
require_once "../../infra/conexao.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: visualizar-user.php");
    exit;
}

$id = (int) $_GET["id"];

$sql_trem = "SELECT id_trem FROM trem WHERE id_usuario = ?";
$stmt_trem = $conexao->prepare($sql_trem);
$stmt_trem->bind_param("i", $id);
$stmt_trem->execute();
$resultado = $stmt_trem->get_result();

if ($resultado->num_rows > 0) {
    header("Location: visualizar-user.php?erro=trens");
    exit;
}
$sql = "DELETE FROM usuario WHERE id_usuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: visualizar-user.php?sucesso=1");
    exit;
} else {
    echo "Erro ao excluir usuário.";
}
?>