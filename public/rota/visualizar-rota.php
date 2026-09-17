<?php

require_once "../../infra/conexao.php";

$sql = "SELECT id_rota, nome_rota, extensao_km, tempo_estimado_min
        FROM rota
        ORDER BY id_rota ASC";

$resultado = $conexao->query($sql);

$rotas = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];

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
                        <a class="nav-link" href="../visualizar-trem.php">Trens</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="rota/visualizar-rota.php">Rotas</a>
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
                        <span class="nav-link d-flex align-items-center gap-2">
                            <ion-icon name="person-circle-outline"></ion-icon>João</span>
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
     <div class="container mt-5">

        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h2 class="mb-0">
                        Lista de rotas cadastradas
                    </h2>

                    <a href="cadastrar-rota.php" class="btn btn-primary d-flex align-items-center gap-2">
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
                                                <td><?= sprintf("RTA-%03d", $rota["id_rota"]) ?></td>
                                                <td><?= $rota["nome_rota"] ?></td>
                                                <td><?= $rota["extensao_km"] ?> Km</td>
                                                <td><?= $rota["tempo_estimado_min"] ?> min</td>
                                                <td class="text-nowrap">

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

                            <h5 class="mb-1">Mapa da Rota</h5>

                            <?php if (count($rotas) > 0) { ?>

                                <select class="form-select form-select-sm border-0 bg-light text-muted mb-3 px-0">

                                    <?php foreach ($rotas as $rota) { ?>

                                        <option>
                                            <?= sprintf("RTA-%03d", $rota["id_rota"]) ?> - <?= $rota["nome_rota"] ?>
                                        </option>

                                    <?php } ?>
                                    
                                     <!--
                                Mapa estático (placeholder).
                                A tabela "rota" ainda não possui dados de geolocalização
                                (estações, trens, coordenadas), então este SVG é apenas
                                ilustrativo. Quando existir uma tabela de geolocalização,
                                trocar este bloco por uma integração real (ex: Leaflet).
                            -->
                            <svg viewBox="0 0 260 220" class="w-100 border rounded bg-white mb-2">
                                <path d="M 130 15 L 130 60 L 90 100 L 90 150 L 60 195"
                                    fill="none" stroke="#0d6efd" stroke-width="3" />

                                <circle cx="130" cy="15" r="5" fill="#212529" />
                                <circle cx="90" cy="100" r="5" fill="#212529" />
                                <circle cx="60" cy="195" r="5" fill="#212529" />

                                <circle cx="130" cy="60" r="6" fill="#198754" />
                                <circle cx="90" cy="150" r="6" fill="#198754" />
                            </svg>

                            <div class="d-flex gap-3 small text-muted mb-2">
                                <span><span class="badge rounded-pill bg-primary">&nbsp;</span> Rota</span>
                                <span><span class="badge rounded-pill bg-dark">&nbsp;</span> Estações</span>
                                <span><span class="badge rounded-pill bg-success">&nbsp;</span> Trens</span>
                            </div>

                            <div class="d-flex align-items-center gap-1 small text-muted">
                                <ion-icon name="sync-outline"></ion-icon>
                                Atualizado agora há pouco
                            </div>

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