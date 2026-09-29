<?php

require_once "../infra/protecao.php";

verificarAdministrador();

require_once "../infra/conexao.php";

$trens_ativos = 12;
$sensores_ativos = 34;
$sensores_inativos = 4;
$alertas_hoje = 15;

$rotas_ativas = 0;
$resultado = $conexao->query("SELECT COUNT(*) AS total FROM rota");

if ($resultado) {
    $linha = $resultado->fetch_assoc();
    $rotas_ativas = $linha["total"];
}

$total_sensores = $sensores_ativos + $sensores_inativos;
$porcentagem_ativos = ($sensores_ativos / $total_sensores) * 100;
$porcentagem_inativos = 100 - $porcentagem_ativos;

$velocidades = [
    ["trem" => "Trem Alpha", "valor" => 88],
    ["trem" => "Trem Beta", "valor" => 75],
    ["trem" => "Trem Gama", "valor" => 60],
    ["trem" => "Trem Delta", "valor" => 67]
];

$alertas = [
    ["hora" => "10:30", "localizacao" => "Trem Alpha", "tipo" => "Excesso de velocidade"],
    ["hora" => "10:12", "localizacao" => "Trem Beta", "tipo" => "Sensor offline"],
    ["hora" => "9:45", "localizacao" => "Linha verde", "tipo" => "Presença detectada"]
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../style/style.css">
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
                    <a class="nav-link" href="tela-geral-home.php">Dashboard</a>
                </li>
                <li class="nav-item">
                     <a class="nav-link" href="sensor/visualizar-sensor.php">Sensores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="trem/visualizar-trem.php">Trens</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="rota/visualizar-rota.php">Rotas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user/visualizar-user.php">Usuários</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="relatorio/visualizar-relatorio.php">Relatórios</a>
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

<div class="container mt-4">

    <h2>
        👋 Bem-vindo,
        <strong class="text-primary"><?= htmlspecialchars($_SESSION["nome_usuario"] ?? "Administrador") ?></strong>
    </h2>

    <hr>

    <!-- Cards de resumo -->
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="fw-bold">Trens Ativos</h6>
                    <ion-icon name="train-outline" class="fs-1"></ion-icon>
                    <span class="display-5 fw-bold ms-2"><?= $trens_ativos ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="fw-bold">Sensores Online</h6>
                    <ion-icon name="wifi-outline" class="fs-1"></ion-icon>
                    <span class="display-5 fw-bold ms-2"><?= $sensores_ativos ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="fw-bold">Alertas Hoje</h6>
                    <ion-icon name="warning-outline" class="fs-1"></ion-icon>
                    <span class="display-5 fw-bold ms-2"><?= $alertas_hoje ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="fw-bold">Rotas ativas</h6>
                    <ion-icon name="location-outline" class="fs-1"></ion-icon>
                    <span class="display-5 fw-bold ms-2"><?= $rotas_ativas ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Status dos sensores e localização dos trens -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="fw-bold text-center mb-4">Status dos Sensores</h6>

                    <div class="progress mb-3" style="height: 30px;">
                        <div class="progress-bar bg-success" style="width: <?= $porcentagem_ativos ?>%">
                            <?= round($porcentagem_ativos) ?>%
                        </div>
                        <div class="progress-bar bg-danger" style="width: <?= $porcentagem_inativos ?>%">
                            <?= round($porcentagem_inativos) ?>%
                        </div>
                    </div>

                    <span class="badge bg-success me-2">Ativos: <?= $sensores_ativos ?></span>
                    <span class="badge bg-danger">Inativos: <?= $sensores_inativos ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="fw-bold text-center mb-3">Localização dos trens</h6>

                    <ul class="list-group">
                        <li class="list-group-item">
                            <ion-icon name="train-outline"></ion-icon> Pátio de Cargas
                        </li>
                        <li class="list-group-item">
                            <ion-icon name="train-outline"></ion-icon> Estação Central
                        </li>
                        <li class="list-group-item">
                            <ion-icon name="train-outline"></ion-icon> Terminal Norte
                        </li>
                        <li class="list-group-item">
                            <ion-icon name="train-outline"></ion-icon> Estação Sul
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Velocidade média e tabela de alertas -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="fw-bold text-center mb-3">Gráfico de velocidade média</h6>

                    <?php foreach ($velocidades as $item) { ?>
                        <p class="mb-1"><?= $item["trem"] ?> (<?= $item["valor"] ?> km/h)</p>
                        <div class="progress mb-3">
                            <div class="progress-bar" style="width: <?= $item["valor"] ?>%"></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="fw-bold text-center mb-3">Tabela de alerta</h6>

                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Hora</th>
                                <th>Localização</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alertas as $alerta) { ?>
                                <tr>
                                    <td><?= $alerta["hora"] ?></td>
                                    <td><?= $alerta["localizacao"] ?></td>
                                    <td><?= $alerta["tipo"] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../script/validacao.js"></script>

</body>
</html>