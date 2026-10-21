<?php

require_once "../../infra/conexao.php";
require_once "../../infra/protecao.php";
verificarAdministrador();

$id_sensor = $_GET["id"] ?? "";

if ($id_sensor == "" || !is_numeric($id_sensor) || $id_sensor <= 0) {
    header("Location: visualizar-sensor.php");
    exit;
}

$sql_leitura = "SELECT id_leitura FROM leitura_sensor WHERE id_sensor = ?";
$stmt_leitura = $conexao->prepare($sql_leitura);
$stmt_leitura->bind_param("i", $id_sensor);
$stmt_leitura->execute();
$resultado = $stmt_leitura->get_result();

if ($resultado->num_rows > 0) {
    header("Location: visualizar-sensor.php?erro=leituras");
    exit;
}

$sql = "DELETE FROM sensor WHERE id_sensor = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_sensor);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    header("Location: visualizar-sensor.php?sucesso=1");
    exit;
} else {
    header("Location: visualizar-sensor.php?erro=exclusao");
    exit;
}
