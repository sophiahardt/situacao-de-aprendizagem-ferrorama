<?php
require_once "../../infra/conexao.php";

$id_trem = $_GET["id"] ?? "";

if ($id_trem == "") {
    header("Location: visualizar-trem.php");
    exit;
}

$sql = "DELETE FROM trem WHERE id_trem = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_trem);

if ($stmt->execute()) {
    header("Location: visualizar-trem.php");
    exit;
} else {
    echo "Erro ao excluir trem.";
}
?>