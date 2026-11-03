<?php
require_once "../infra/protecao.php";
verificarLogin();

$trens_ativos = 0;
$resultado = $conexao->query("SELECT COUNT(*) AS total FROM trem");

if ($resultado) {
    $linha = $resultado->fetch_assoc();
    $trens_ativos = $linha["total"];
}

$sensores_ativos = 0;
$resultado = $conexao->query("SELECT COUNT(*) AS total FROM sensor");

if ($resultado) {
    $linha = $resultado->fetch_assoc();
    $sensores_ativos = $linha["total"];
}

$rotas_ativas = 0;
$resultado = $conexao->query("SELECT COUNT(*) AS total FROM rota");

if ($resultado) {
    $linha = $resultado->fetch_assoc();
    $rotas_ativas = $linha["total"];
}

$ultimas_rotas = $conexao->query("
    SELECT nome_rota, extensao
    FROM rota
    ORDER BY id_rota DESC
    LIMIT 3
");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Ferrorama</title>
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
                    <?php if (ehAdministrador()) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="user/visualizar-user.php">Usuários</a>
                        </li>
                    <?php } ?>
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
                        <a class="btn btn-outline-light btn-sm d-flex align-items-center gap-2" href="logout.php">
                            <ion-icon name="log-out-outline"></ion-icon>
                            <span>Sair</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid dashboard-container">
        <section class="row g-3 mb-3">
            <div class="col-lg-4">
                <div class="card card-dashboard clima-card h-100">
                    <div class="card-body">
                        <h6 class="fw-bold">Clima atual</h6>

                        <p class="clima-local">
                            <ion-icon name="location"></ion-icon>
                            <span id="clima-local">Localização</span>
                        </p>

                        <div class="d-flex align-items-center gap-3 clima-temperatura">
                            <ion-icon name="partly-sunny" id="clima-icone"></ion-icon>
                            <div>
                                <h2 id="clima-temperatura">--°C</h2>
                                <span id="clima-condicao">Carregando clima...</span>
                            </div>
                        </div>
                        <hr>

                        <div class="row text-center">
                            <div class="col-6">
                                <p class="mb-1">
                                    <ion-icon name="water-outline"></ion-icon>
                                    Umidade
                                </p>

                                <strong id="clima-umidade">--%</strong>
                            </div>

                            <div class="col-6">
                                <p class="mb-1">
                                    <ion-icon name="navigate-outline"></ion-icon>
                                    Vento
                                </p>
                                <strong id="clima-vento">-- km/h</strong>
                            </div>
                        </div>

                        <div class="clima-atualizacao">
                            <small id="clima-atualizacao">Aguardando atualização...</small>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-8">
                <div class="banner-dashboard h-100 d-flex align-items-center">
                    <div class="banner-conteudo">
                        <h2>Ferrorama</h2>
                        <p>
                            Monitoramento ferroviário em tempo real
                            para mais segurança e eficiência.
                        </p>
                        <a href="rota/visualizar-rota.php" class="btn btn-light btn-sm">
                            Ver rotas de operações
                            <ion-icon name="arrow-forward-outline"></ion-icon>
                        </a>
                    </div>
                    <ion-icon name="train-outline" class="banner-icone ms-auto"></ion-icon>
                </div>
            </div>
        </section>

        <section class="row g-3 mb-3">
            <div class="col-6 col-md-4 col-lg">
                <a href="trem/visualizar-trem.php" class="card card-atalho">
                    <div class="card-body">
                        <ion-icon name="train-outline"></ion-icon>
                        <h6 class="titulo-secao">Trens</h6>
                        <p>Gerencie os trens cadastrados e acompanhe suas operações.</p>
                        <span>Acessar <ion-icon name="arrow-forward-outline"></ion-icon></span>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-4 col-lg">
                <a href="rota/visualizar-rota.php" class="card card-atalho">
                    <div class="card-body">
                        <ion-icon name="git-network-outline"></ion-icon>
                        <h6 class="titulo-secao">Rotas</h6>
                        <p>Visualize e administre as rotas ferroviárias.</p>
                        <span>Acessar <ion-icon name="arrow-forward-outline"></ion-icon></span>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-4 col-lg">
                <a href="sensor/visualizar-sensor.php" class="card card-atalho">
                    <div class="card-body">
                        <ion-icon name="radio-outline"></ion-icon>
                        <h6 class="titulo-secao">Sensores</h6>
                        <p>Acompanhe os sensores e suas informações.</p>
                        <span>Acessar <ion-icon name="arrow-forward-outline"></ion-icon></span>
                    </div>
                </a>
            </div>

            <?php if (ehAdministrador()) { ?>
                <div class="col-6 col-md-4 col-lg">
                    <a href="user/visualizar-user.php" class="card card-atalho">
                        <div class="card-body">
                            <ion-icon name="person-outline"></ion-icon>
                            <h6 class="titulo-secao">Usuários</h6>
                            <p>Gerencie os usuários do sistema.</p>
                            <span>Acessar <ion-icon name="arrow-forward-outline"></ion-icon></span>
                        </div>
                    </a>
                </div>
            <?php } ?>

            <div class="col-6 col-md-4 col-lg">
                <a href="relatorio/visualizar-relatorio.php" class="card card-atalho">
                    <div class="card-body">
                        <ion-icon name="document-text-outline"></ion-icon>
                        <h6 class="titulo-secao">Relatórios</h6>
                        <p>Emita e consulte relatórios operacionais.</p>
                        <span>Acessar <ion-icon name="arrow-forward-outline"></ion-icon></span>
                    </div>
                </a>
            </div>

        </section>



        <section class="row g-3 mb-3">
            <div class="col-lg-6">
                <div class="card card-dashboard h-100">
                    <div class="card-body">
                        <h6 class="titulo-secao">Resumo da operação</h6>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="card card-resumo">
                                    <div class="card-body">
                                        <ion-icon name="train-outline"></ion-icon>

                                        <div>
                                            <small>Trens cadastrados</small>
                                            <h4><?= $trens_ativos ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="card card-resumo">
                                    <div class="card-body">
                                        <ion-icon name="git-network-outline"></ion-icon>

                                        <div>
                                            <small>Rotas cadastradas</small>
                                            <h4><?= $rotas_ativas ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="card card-resumo">
                                    <div class="card-body">
                                        <ion-icon name="radio-outline"></ion-icon>

                                        <div>
                                            <small>Sensores cadastrados</small>
                                            <h4><?= $sensores_ativos ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="card card-resumo">
                                    <div class="card-body">
                                        <ion-icon name="shield-checkmark-outline"></ion-icon>

                                        <div>
                                            <small>Sistema</small>
                                            <h4 class="texto-normal">Operacional</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-6">
                <div class="card card-dashboard h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="titulo-secao mb-0">Últimos eventos</h6>
                            <span class="texto-azul">Monitoramento</span>
                        </div>

                        <div class="evento-item">
                            <span class="evento-ponto alerta"></span>

                            <div>
                                <strong>Monitoramento de sensores</strong>
                                <small>Consulte os dados dos sensores cadastrados.</small>
                            </div>

                            <span class="badge bg-warning-subtle text-warning">Acompanhar</span>
                        </div>

                        <div class="evento-item">
                            <span class="evento-ponto informacao"></span>

                            <div>
                                <strong>Trens cadastrados</strong>
                                <small><?= $trens_ativos ?> trem(ns) registrado(s) no sistema.</small>
                            </div>

                            <span class="badge bg-primary-subtle text-primary">Informação</span>
                        </div>

                        <div class="evento-item">
                            <span class="evento-ponto normal"></span>

                            <div>
                                <strong>Rotas cadastradas</strong>
                                <small><?= $rotas_ativas ?> rota(s) registrada(s) no sistema.</small>
                            </div>

                            <span class="badge bg-success-subtle text-success">Normal</span>
                        </div>

                        <div class="evento-item">
                            <span class="evento-ponto normal"></span>

                            <div>
                                <strong>Sistema Ferrorama</strong>
                                <small>Painel de gerenciamento disponível.</small>
                            </div>

                            <span class="badge bg-success-subtle text-success">Normal</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <section class="row g-3 mb-3">
            <div class="col-lg-6">
                <div class="card card-dashboard h-100">
                    <div class="card-body">
                        <h6 class="titulo-secao">Monitoramento ferroviário</h6>

                        <p class="texto-apoio">
                            Espaço reservado para exibição dos dados de velocidade dos trens.
                        </p>

                        <div class="grafico-area">
                            <canvas id="graficoTrens"></canvas>
                        </div>

                    </div>
                </div>
            </div>


            <div class="col-lg-6">
                <div class="card card-dashboard h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="titulo-secao mb-0">Rotas cadastradas</h6>

                            <a href="rota/visualizar-rota.php" class="link-pequeno">
                                Ver todas
                                <ion-icon name="arrow-forward-outline"></ion-icon>
                            </a>
                        </div>

                        <?php if ($ultimas_rotas && $ultimas_rotas->num_rows > 0) { ?>

                            <?php while ($rota = $ultimas_rotas->fetch_assoc()) { ?>

                                <div class="rota-item">
                                    <ion-icon name="location-outline"></ion-icon>

                                    <div>
                                        <strong><?= htmlspecialchars($rota["nome_rota"]) ?></strong>
                                        <small><?= number_format($rota["extensao"], 2, ",", ".") ?> km de extensão</small>
                                    </div>

                                    <span class="badge bg-success-subtle text-success">Cadastrada</span>
                                </div>

                            <?php } ?>

                        <?php } else { ?>
                            <p class="texto-apoio">
                                Nenhuma rota cadastrada até o momento.
                            </p>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </section>
    </main>

</body>
</html>