<?php

require_once "../../infra/protecao.php";

verificarAdministrador();

require_once "../../infra/conexao.php";

$id_rota = $_GET["id"] ?? "";

if ($id_rota == "") {
    header("Location: visualizar-rota.php");
    exit;
}

$sql = "DELETE FROM rota WHERE id_rota = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_rota);

if ($stmt->execute()) {
    header("Location: visualizar-rota.php");
    exit;
} else {
    echo "Erro ao excluir rota.";
}
?>