<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/conexao.php";

// Calcula o caminho de uma página pública a partir da pasta /public/. 
// Isso evita redirecionamentos incorretos em diferentes caminhos do projeto.
function caminhoPublico($arquivo)
{
    $script = $_SERVER["SCRIPT_NAME"] ?? "";

    $posicao = strpos($script, "/public/");

    if ($posicao !== false) {
        return substr($script, 0, $posicao + 8) . $arquivo;
    }

    return "/public/" . $arquivo;
}

// Impede o acesso de usuários que não iniciaram sessão.
function verificarLogin()
{
    if (!isset($_SESSION["id_usuario"])) {
        header("Location: " . caminhoPublico("tela-login.php"));
        exit;
    }
}

// Confere se o usuário possui acesso de administrador.
function ehAdministrador()
{
    if (!isset($_SESSION["id_cargo"])) {
        return false;
    }

    if ((int) $_SESSION["id_cargo"] === 1) {
        return true;
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

    return $cargo &&
        in_array(strtolower(trim($cargo["nome_cargo"])), ["administrador", "admin"], true);
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