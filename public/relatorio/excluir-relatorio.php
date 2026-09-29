<?php
session_start();
require_once "../../infra/conexao.php";
require_once "../../infra/protecao.php";
verificarAdministrador();
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: visualizar-relatorio.php");
    exit;
}
$id = (int) $_GET["id"];
$sql = "DELETE FROM relatorio WHERE id_relatorio = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    header("Location: visualizar-relatorio.php?sucesso=1");
    exit;
}
echo "Erro ao excluir relatório: " . $stmt->error;
?>
