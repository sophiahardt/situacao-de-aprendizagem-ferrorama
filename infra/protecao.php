<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/conexao.php";

function caminhoPublico($arquivo)
{
    $script = $_SERVER["SCRIPT_NAME"] ?? "";

    $posicao = strpos($script, "/public/");

    if ($posicao !== false) {
        return substr($script, 0, $posicao + 8) . $arquivo;
    }

    return "/public/" . $arquivo;
}

function verificarLogin()
{
    if (!isset($_SESSION["id_usuario"])) {
        header("Location: " . caminhoPublico("tela-login.php"));
        exit;
    }
}

function ehAdministrador()
{
    if (isset($_SESSION["eh_administrador"])) {
        return $_SESSION["eh_administrador"];
    }

    if (!isset($_SESSION["id_cargo"])) {
        return false;
    }

    global $conexao;

    $sql = "SELECT nome_cargo FROM cargo WHERE id_cargo = ?";
    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $_SESSION["id_cargo"]);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $cargo = $resultado->fetch_assoc();

    $_SESSION["eh_administrador"] =
        $cargo &&
        strtolower(trim($cargo["nome_cargo"])) === "administrador";

    return $_SESSION["eh_administrador"];
}

function verificarAdministrador()
{
    verificarLogin();

    if (!ehAdministrador()) {
        header("Location: " . caminhoPublico("acesso-negado.php"));
        exit;
    }
}

?>