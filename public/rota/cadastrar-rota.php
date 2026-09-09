<?php

require_once "../../infra/conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome_rota = $_POST["nome_rota"] ?? "";
    $extensao_km = $_POST["extensao_km"] ?? "";
    $tempo_estimado_min = $_POST["tempo_estimado_min"] ?? "";

    if ($nome_rota == "" || $extensao_km == "" || $tempo_estimado_min == "") {

        $mensagem = "Preencha todos os campos.";

    } else {

        $sql = "INSERT INTO rota
                (id_rota, nome_rota, extensao_km, tempo_estimado_min)
                VALUES (NULL, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            "sss",
            $nome_rota,
            $extensao_km,
            $tempo_estimado_min
        );

        if ($stmt->execute()) {

            header("Location: visualizar-rota.php");
            exit;

        } else {

            $mensagem = "Erro ao cadastrar a rota.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Rota</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script type="module"
        src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js">
    </script>

    <script nomodule
        src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js">
    </script>

    <link rel="stylesheet" href="../../style/style.css">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-sistema">

        <div class="container-fluid">

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav me-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="../tela-geral-home.php">
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Sensores
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Trens
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="visualizar-rota.php">
                            Rotas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Funcionários
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Relatórios
                        </a>
                    </li>

                </ul>