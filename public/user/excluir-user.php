<?php

require_once "../../infra/conexao.php";
require_once "../../infra/protecao.php";
verificarAdministrador();

$id_usuario = $_GET["id"] ?? "";

if ($id_usuario == "" || !is_numeric($id_usuario) || $id_usuario <= 0) {
    header("Location: visualizar-user.php");
    exit;
}

$id_usuario = (int) $id_usuario;

$sql_trem = "SELECT id_trem FROM trem WHERE id_usuario = ?";
$stmt_trem = $conexao->prepare($sql_trem);
$stmt_trem->bind_param("i", $id_usuario);
$stmt_trem->execute();

$resultado = $stmt_trem->get_result();

if ($resultado->num_rows > 0) {
    header("Location: visualizar-user.php?erro=trens");
    exit;
}

$sql = "DELETE FROM usuario WHERE id_usuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_usuario);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    header("Location: visualizar-user.php?sucesso=1");
    exit;
} else {
    header("Location: visualizar-user.php?erro=exclusao");
    exit;
}
?>
