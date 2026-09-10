<?php

require_once "../../infra/conexao.php";

$sql = "SELECT sensor.*,
               rota.nome_rota,
               trem.nome_trem
        FROM sensor
        LEFT JOIN rota ON sensor.id_rota = rota.id_rota
        LEFT JOIN trem ON sensor.id_trem = trem.id_trem";

$resultado = $conexao->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Visualização de Sensores</title>

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
                        <a class="nav-link" href="../tela-geral-home.php">
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../sensor/visualizar-sensor.php">
                            Sensores
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../trem/visualizar-trem.php">
                            Trens
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../rota/visualizar-rota.php">
                            Rotas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../funcionario/visualizar-funcionario.php">
                            Funcionários
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../relatorio/visualizar-relatorio.php">
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

                        <a class="btn btn-outline-light btn-sm d-flex align-items-center gap-2" href="#">

                            <ion-icon name="log-out-outline"></ion-icon>

                            <span>Sair</span>

                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </nav>


    <div class="container-fluid p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h1 class="h3">
                Lista de sensores cadastrados
            </h1>

            <button class="btn btn-primary"
                style="background-color: #003399;"
                onclick="window.location.href='cadastrar-sensor.php'">

                <ion-icon name="add-circle"></ion-icon>

                Novo sensor

            </button>

        </div>


        <div class="card shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>ID</th>

                                <th>Nome</th>

                                <th>Localização</th>

                                <th>Tipo de dado</th>

                                <th>Ações</th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php while ($sensor = $resultado->fetch_assoc()) { ?>

                                <tr>

                                    <td>
                                        <?= $sensor["id_sensor"] ?>
                                    </td>

                                    <td>
                                        <?= $sensor["nome_sensor"] ?>
                                    </td>

                                    <td>

                                        <?php

                                        if ($sensor["localizacao"] == "rota") {

                                            echo $sensor["nome_rota"];

                                        } else {

                                            echo $sensor["nome_trem"];

                                        }

                                        ?>

                                    </td>

                                    <td>

                                        <?= $sensor["tipo_sensor"] ?>

                                    </td>

                                    <td>

                                        <a href="editar-sensor.php?id=<?= $sensor["id_sensor"] ?>"
                                            class="btn btn-warning btn-sm">

                                            <ion-icon name="pencil"></ion-icon>

                                            Editar

                                        </a>


                                        <a href="excluir-sensor.php?id=<?= $sensor["id_sensor"] ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Tem certeza que deseja excluir este sensor?');">

                                            <ion-icon name="trash"></ion-icon>

                                            Excluir

                                        </a>

                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>