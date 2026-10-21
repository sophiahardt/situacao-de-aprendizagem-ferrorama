<?php


require_once "../../infra/protecao.php";
verificarAdministrador();
require_once "../../infra/conexao.php";


$id_rota = $_GET["id"] ?? "";


if ($id_rota == "" || !filter_var($id_rota, FILTER_VALIDATE_INT) || $id_rota <= 0) {
    header("Location: visualizar-rota.php?erro=exclusao");
    exit;
}


$sql = "SELECT id_trem FROM trem_rota WHERE id_rota = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_rota);
$stmt->execute();
$resultado = $stmt->get_result();


if ($resultado->num_rows > 0) {
    header("Location: visualizar-rota.php?erro=trens");
    exit;
}
