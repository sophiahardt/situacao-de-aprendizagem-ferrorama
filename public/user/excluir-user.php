<?php
session_start();
require_once "../../infra/conexao.php";
require_once "../../infra/protecao.php";
verificarAdministrador();
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: visualizar-user.php");
    exit;
}
$id = (int) $_GET["id"];
$sql = "DELETE FROM usuario WHERE id_usuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    header("Location: visualizar-user.php");
    exit;
}
echo "Erro ao excluir usuário: " . $stmt->error;
?>