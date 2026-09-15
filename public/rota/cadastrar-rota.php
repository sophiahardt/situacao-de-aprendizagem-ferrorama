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
                  <ul class="navbar-nav ms-auto align-items-center">

                    <li class="nav-item me-3">

                        <span class="nav-link d-flex align-items-center gap-2">

                            <ion-icon name="person-circle-outline"></ion-icon>

                            João

                        </span>

                    </li>

                    <li class="nav-item">

                        
                            class="btn btn-outline-light btn-sm d-flex align-items-center gap-2"
                            href="#">

                            <ion-icon name="log-out-outline"></ion-icon>

                            <span>Sair</span>

                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </nav>

     <div class="container mt-5">

        <div class="card">

            <div class="card-body">

                <h2 class="text-center mb-3">
                    Cadastrar nova rota
                </h2>

                <p class="text-center text-muted">
                    Preencha as informações para cadastrar uma nova rota no sistema
                </p>

                <hr>


                <?php if ($mensagem != "") { ?>

                    <p class="text-center text-success">
                        <?= $mensagem ?>
                    </p>

                <?php } ?>


                <form method="POST">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="mb-3">

                                <label class="form-label">
                                    Nome da Rota
                                </label>

                                <input
                                    type="text"
                                    name="nome_rota"
                                    class="form-control"
                                    placeholder="Digite o nome"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Extensão (Km)
                                </label>

                                <input
                                    type="number"
                                    step="0.01"
                                    name="extensao_km"
                                    class="form-control"
                                    placeholder="Ex: 45"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Tempo estimado (Min)
                                </label>
                                <input