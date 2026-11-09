<?php

require_once "../../infra/protecao.php";
verificarAdministrador();
require_once "../../infra/conexao.php";

$id_sensor = $_GET["id"] ?? "";

if ($id_sensor == "" || !filter_var($id_sensor, FILTER_VALIDATE_INT) || $id_sensor <= 0) {
    header("Location: visualizar-sensor.php?erro=exclusao");
    exit;
}

$id_sensor = (int) $id_sensor;

$sql = "SELECT id_leitura
        FROM leitura_sensor
        WHERE id_sensor = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_sensor);
$stmt->execute();

$resultado = $stmt->get_result();

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
?>
