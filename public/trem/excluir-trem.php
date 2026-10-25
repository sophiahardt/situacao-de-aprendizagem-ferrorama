<?php

require_once "../../infra/conexao.php";
require_once "../../infra/protecao.php";
verificarAdministrador();

$id_trem = $_GET["id"] ?? "";

if ($id_trem == "" || !filter_var($id_trem, FILTER_VALIDATE_INT) || $id_trem <= 0) {
    header("Location: visualizar-trem.php?erro=exclusao");
    exit;
}

$id_trem = (int) $id_trem;

$sql_rota = "SELECT id_rota FROM trem_rota WHERE id_trem = ?";
$stmt_rota = $conexao->prepare($sql_rota);
$stmt_rota->bind_param("i", $id_trem);
$stmt_rota->execute();
$resultado_rota = $stmt_rota->get_result();


if ($resultado_rota->num_rows > 0) {
    header("Location: visualizar-trem.php?erro=rotas");
    exit;
}

$sql_sensor = "SELECT id_sensor FROM sensor WHERE id_trem = ?";
$stmt_sensor = $conexao->prepare($sql_sensor);
$stmt_sensor->bind_param("i", $id_trem);
$stmt_sensor->execute();
$resultado_sensor = $stmt_sensor->get_result();


if ($resultado_sensor->num_rows > 0) {
    header("Location: visualizar-trem.php?erro=sensores");
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