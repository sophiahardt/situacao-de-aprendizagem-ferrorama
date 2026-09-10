<?php
require_once "../../infra/conexao.php";

$id_sensor = $_GET["id"] ?? "";

if ($id_sensor == "") {
    header("Location: visualizar-sensor.php");
    exit;
}

$sql = "DELETE FROM sensor WHERE id_sensor = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_sensor);

if ($stmt->execute()) {
    header("Location: visualizar-sensor.php");
    exit;
} else {
    echo "Erro ao excluir sensor.";
}
?>