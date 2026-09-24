<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function verificarLogin()
{
    if (!isset($_SESSION["id_usuario"])) {
        header("Location: ../tela-login.php");
        exit;
    }
}

function verificarAdministrador()
{
    verificarLogin();

    if ($_SESSION["id_cargo"] != 1) {
        header("Location: ../acesso-negado.php");
        exit;
    }
}

?>