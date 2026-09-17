```php
<?php

require_once "../../infra/conexao.php";

$sql = "SELECT id_rota, nome_rota, extensao_km, tempo_estimado_min
        FROM rota
        ORDER BY id_rota ASC";

$resultado = $conexao->query($sql);

$rotas = [];

if ($resultado) {
    $rotas = $resultado->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rotas Cadastradas</title>

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

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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
                        <a class="nav-link" href="../visualizar-trem.php">Trens</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="visualizar-rota.php">Rotas</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../funcionario/visualizar-funcionario.php">Funcionários</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../relatorio/visualizar-relatorio.php">Relatórios</a>
                    </li>

                </ul>

                <ul class="navbar-nav ms-auto align-items-center">

                    <li class="nav-item me-3">

                        <span class="nav-link">
                            <ion-icon name="person-circle-outline"></ion-icon>
                            João
                        </span>

                    </li>

                    <li class="nav-item">

                        <a class="btn btn-outline-light btn-sm" href="#">

                            <ion-icon name="log-out-outline"></ion-icon>
                            Sair

                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </nav>


    <div class="container mt-5">

        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h2 class="mb-0">
                        Lista de rotas cadastradas
                    </h2>

                    <a href="cadastrar-rota.php" class="btn btn-primary">

                        <ion-icon name="add-outline"></ion-icon>
                        Nova rota

                    </a>

                </div>

                <hr>


                <div class="row">


                    <div class="col-lg-8">

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead>

                                    <tr>

                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Extensão</th>
                                        <th>Tempo estimado</th>
                                        <th></th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <?php if (count($rotas) > 0) { ?>

                                        <?php foreach ($rotas as $rota) { ?>

                                            <tr>

                                                <td>
                                                    <?= sprintf("RTA-%03d", $rota["id_rota"]) ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($rota["nome_rota"]) ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($rota["extensao_km"]) ?> Km
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($rota["tempo_estimado_min"]) ?> min
                                                </td>

                                                <td>

                                                    <a href="excluir-rota.php?id=<?= $rota["id_rota"] ?>"
                                                        class="btn btn-outline-danger btn-sm rounded-circle"
                                                        onclick="return confirm('Deseja realmente excluir esta rota?');">

                                                        <ion-icon name="trash-outline"></ion-icon>

                                                    </a>


                                                    <a href="editar-rota.php?id=<?= $rota["id_rota"] ?>"
                                                        class="btn btn-outline-primary btn-sm rounded-circle">

                                                        <ion-icon name="create-outline"></ion-icon>

                                                    </a>

                                                </td>

                                            </tr>

                                        <?php } ?>

                                    <?php } else { ?>

                                        <tr>

                                            <td colspan="5" class="text-center text-muted">

                                                Nenhuma rota cadastrada.

                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    </div>


                    <div class="col-lg-4">

                        <div class="bg-light border rounded p-3">

                            <h5 class="mb-1">
                                Mapa da Rota
                            </h5>


                            <?php if (count($rotas) > 0) { ?>


                                <select class="form-select form-select-sm mb-3">

                                    <?php foreach ($rotas as $rota) { ?>

                                        <option>

                                            <?= sprintf("RTA-%03d", $rota["id_rota"]) ?>

                                            -

                                            <?= htmlspecialchars($rota["nome_rota"]) ?>

                                        </option>

                                    <?php } ?>

                                </select>


                                <div class="border rounded bg-white p-4 text-center mb-2">

                                    <p class="mb-2">
                                        Início
                                    </p>

                                    <div class="border-start border-primary border-3 mx-auto"
                                        style="height: 50px;">
                                    </div>

                                    <p class="mb-2 mt-2">
                                        Estação
                                    </p>

                                    <div class="border-start border-primary border-3 mx-auto"
                                        style="height: 50px;">
                                    </div>

                                    <p class="mb-0">
                                        Destino
                                    </p>

                                </div>


                            <?php } else { ?>

                                <p class="text-muted">
                                    Nenhuma rota cadastrada ainda.
                                </p>

                            <?php } ?>


                        </div>

                    </div>


                </div>

            </div>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    <script src="../../script/validacao.js">
    </script>

</body>

</html>
```
