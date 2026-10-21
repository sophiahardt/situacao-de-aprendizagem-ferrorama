<?php

require_once "../../infra/conexao.php";
require_once "../../infra/protecao.php";
verificarAdministrador();

$id_trem = $_GET["id"] ?? "";

if ($id_trem == "" || !is_numeric($id_trem) || $id_trem <= 0) {
    header("Location: visualizar-trem.php");
    exit;
}

$sql_sensor = "SELECT id_sensor FROM sensor WHERE id_trem = ?";
$stmt_sensor = $conexao->prepare($sql_sensor);
$stmt_sensor->bind_param("i", $id_trem);
$stmt_sensor->execute();
$resultado = $stmt_sensor->get_result();

if ($resultado->num_rows > 0) {
    header("Location: visualizar-trem.php?erro=sensores");
    exit;
}

$sql_rota = "DELETE FROM trem_rota WHERE id_trem = ?";
$stmt_rota = $conexao->prepare($sql_rota);
$stmt_rota->bind_param("i", $id_trem);

if (!$stmt_rota->execute()) {
    header("Location: visualizar-trem.php?erro=exclusao");
    exit;
}

$sql = "DELETE FROM trem WHERE id_trem = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_trem);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    header("Location: visualizar-trem.php?sucesso=1");
    exit;
} else {
    header("Location: visualizar-trem.php?erro=exclusao");
    exit;
}
?>