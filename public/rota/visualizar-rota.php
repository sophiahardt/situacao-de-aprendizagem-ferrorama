<?php
session_start();

require_once "../../infra/conexao.php";

$rotas = $conexao->query("SELECT id_rota, nome_rota, extensao, tempo_estimado FROM rota");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../../style/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-sistema">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../tela-geral-home.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../sensor/visualizar-sensor.php">Sensores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../trem/visualizar-trem.php">Trens</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="visualizar-rota.php">Rotas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../user/visualizar-user.php">Usuários</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../relatorio/visualizar-relatorio.php">Relatórios</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-3">
                    <span class="nav-link d-flex align-items-center gap-2">
                        <ion-icon name="person-circle-outline"></ion-icon>
                        <?= htmlspecialchars($_SESSION["nome_usuario"] ?? "Usuário") ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm d-flex align-items-center gap-2" href="../tela-login.php">
                        <ion-icon name="log-out-outline"></ion-icon>
                        <span>Sair</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
