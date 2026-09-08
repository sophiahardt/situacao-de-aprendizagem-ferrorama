<?php

require_once "../../infra/conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome_sensor = $_POST["nome_sensor"];
    $tipo_sensor = $_POST["tipo_sensor"];
    $localizacao = $_POST["localizacao"];
    $id_localizacao = $_POST["id_localizacao"];

    if ($nome_sensor == "" || $tipo_sensor == "" || $id_localizacao == "") {

        $mensagem = "Preencha todos os campos.";

    } else {

        $id_rota = null;
        $id_trem = null;

        if ($localizacao == "rota") {

            $id_rota = $id_localizacao;

        } else {

            $id_trem = $id_localizacao;
        }

        $sql = "INSERT INTO sensor
                (nome_sensor, tipo_sensor, localizacao, id_trem, id_rota)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            "sssii",
            $nome_sensor,
            $tipo_sensor,
            $localizacao,
            $id_trem,
            $id_rota
        );

        if ($stmt->execute()) {

            $mensagem = "Sensor cadastrado com sucesso!";

        } else {

            $mensagem = "Erro ao cadastrar o sensor.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Sensor</title>

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
                        <a class="nav-link" href="#">
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

                        <a
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
                    Cadastrar novo sensor
                </h2>

                <p class="text-center text-muted">
                    Preencha as informações para cadastrar um novo sensor no sistema
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
                                    ID do Sensor
                                </label>

                                <input
                                    type="text"
                                    name="id_sensor"
                                    class="form-control"
                                    placeholder="Informe o id do sensor"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Nome do Sensor
                                </label>

                                <input
                                    type="text"
                                    name="nome_sensor"
                                    class="form-control"
                                    placeholder="Informe o nome do sensor"
                                    required>

                            </div>


                            <div class="localizacao-sensor">

                                <label class="form-label">
                                    Localização do Sensor
                                </label>


                                <div class="opcoes-localizacao">

                                    <input
                                        type="radio"
                                        id="rota"
                                        name="localizacao"
                                        value="rota"
                                        checked
                                        onchange="alterarLocalizacao()">

                                    <label
                                        for="rota"
                                        class="btn-localizacao">

                                        Rota

                                    </label>


                                    <input
                                        type="radio"
                                        id="trem"
                                        name="localizacao"
                                        value="trem"
                                        onchange="alterarLocalizacao()">

                                    <label
                                        for="trem"
                                        class="btn-localizacao">

                                        Trem

                                    </label>

                                </div>


                                <select
                                    name="id_localizacao"
                                    id="id_localizacao"
                                    class="form-select"
                                    required>

                                    <option selected disabled>
                                        Selecione a rota vinculada ao sensor
                                    </option>

                                </select>

                            </div>

                        </div>


                        <!-- LADO DIREITO -->

                        <div class="col-md-6">

                            <div class="mb-3">

                                <label class="form-label">
                                    Tipo de dado
                                </label>

                                <select
                                    name="tipo_sensor"
                                    class="form-select"
                                    required>

                                    <option selected disabled>
                                        Selecione o tipo de dado coletado
                                    </option>

                                    <option value="Temperatura">
                                        Temperatura
                                    </option>

                                    <option value="Velocidade">
                                        Velocidade
                                    </option>

                                    <option value="Presença">
                                        Presença
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>


                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                        <button
                            type="button"
                            class="btn btn-light"
                            onclick="window.location.href='../tela-geral-home.php'">

                            <ion-icon name="close-outline"></ion-icon>

                            Cancelar

                        </button>


                        <button
                            type="submit"
                            class="btn btn-primary">

                            <ion-icon name="save-outline"></ion-icon>

                            Salvar

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    <script src="../../script/validacao.js">
    </script>

</body>

</html>