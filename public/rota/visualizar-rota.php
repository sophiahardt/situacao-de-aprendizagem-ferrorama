<?php

require_once "../../infra/protecao.php";

verificarLogin();

require_once "../../infra/conexao.php";

$sql = "SELECT id_rota, nome_rota, extensao, tempo_estimado_minutos
        FROM rota
        ORDER BY id_rota ASC";

$rotas = $conexao->query($sql);

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

        <button class="navbar-toggler"
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
                    <a class="nav-link active" href="visualizar-rota.php">
                        Rotas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../user/visualizar-user.php">
                        Usuários
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

                        <?= htmlspecialchars($_SESSION["nome_usuario"] ?? "Usuário") ?>

                    </span>

                </li>

                <li class="nav-item">

                    <a class="btn btn-outline-light btn-sm d-flex align-items-center gap-2"
                        href="../tela-login.php">

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

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h2 class="h3">
                    Lista de rotas cadastradas
                </h2>

                <a href="cadastro-rota.php"
                    class="btn btn-primary d-flex align-items-center gap-2">

                    <ion-icon name="add-outline"></ion-icon>

                    Nova rota

                </a>

            </div>

            <hr>

            <div class="row">

                <div class="col-md-7">

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

                            <?php if ($rotas && $rotas->num_rows > 0): ?>

                                <?php while ($rota = $rotas->fetch_assoc()): ?>

                                    <tr>

                                        <td>
                                            RTA-<?= str_pad($rota["id_rota"], 3, "0", STR_PAD_LEFT) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($rota["nome_rota"]) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($rota["extensao"]) ?> Km
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($rota["tempo_estimado_minutos"]) ?> min
                                        </td>

                                        <td>

                                            <div class="d-flex gap-2">

                                                <a href="excluir-rota.php?id=<?= $rota["id_rota"] ?>"
                                                    class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Deseja realmente excluir esta rota?')">

                                                    <ion-icon name="trash-outline"></ion-icon>

                                                </a>

                                                <a href="editar-rota.php?id=<?= $rota["id_rota"] ?>"
                                                    class="btn btn-outline-primary btn-sm">

                                                    <ion-icon name="create-outline"></ion-icon>

                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>

                                    <td colspan="5"
                                        class="text-center text-muted py-4">

                                        Nenhuma rota cadastrada.

                                    </td>

                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

                <div class="col-md-5">

                    <div class="bg-light rounded p-3">

                        <h5 class="mb-3">
                            Mapa da Rota
                        </h5>

                        <select class="form-select mb-3">

                            <?php

                            if ($rotas) {

                                $rotas->data_seek(0);

                                while ($rota = $rotas->fetch_assoc()) {

                            ?>

                                    <option value="<?= $rota["id_rota"] ?>">

                                        RTA-<?= str_pad($rota["id_rota"], 3, "0", STR_PAD_LEFT) ?>

                                        -

                                        <?= htmlspecialchars($rota["nome_rota"]) ?>

                                    </option>

                            <?php

                                }

                            }

                            ?>

                        </select>

                        <img src=""
                            alt="Mapa da Rota"
                            class="img-fluid rounded border">

                        <div class="d-flex justify-content-around mt-3">

                            <span class="d-flex align-items-center gap-1">

                                <ion-icon name="ellipse"
                                    style="color: blue;">
                                </ion-icon>

                                Rota

                            </span>

                            <span class="d-flex align-items-center gap-1">

                                <ion-icon name="ellipse"
                                    style="color: black;">
                                </ion-icon>

                                Estações

                            </span>

                            <span class="d-flex align-items-center gap-1">

                                <ion-icon name="ellipse"
                                    style="color: green;">
                                </ion-icon>

                                Trens

                            </span>

                        </div>

                        <p class="text-muted text-center mt-2 mb-0">

                            <ion-icon name="refresh-outline"></ion-icon>

                            Atualizado agora há pouco

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../script/validacao.js"></script>

</body>

</html>