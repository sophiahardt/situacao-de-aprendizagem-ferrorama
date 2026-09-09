<?php
session_start();
include_once("../../infra/conexao.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/login.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: visualizar-user.php");
    exit;
}

$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: visualizar-user.php?mensagem=Usuário excluído com sucesso");
        exit;
    }

    $erro = "Não foi possível excluir o usuário.";
}
?>