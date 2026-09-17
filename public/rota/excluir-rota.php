<?php

require_once "../../infra/conexao.php";

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {

    $id_rota = $_GET["id"];

    $sql = "DELETE FROM rota WHERE id_rota = ?";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param("i", $id_rota);

    $stmt->execute();
}

header("Location: visualizar-rota.php");
exit;

?>