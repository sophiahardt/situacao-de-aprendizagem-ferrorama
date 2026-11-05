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
    <nav class="navbar navbar-expand-lg navbar-dark navbar-sistema navbar-fixa">
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
                            <span id="clima-local">Aguardando localização...</span>
                        </p>

                        <div class="d-flex align-items-center gap-3 clima-temperatura">
                            <ion-icon name="partly-sunny" id="clima-icone"></ion-icon>
                            <div>
                                <h2 id="clima-temperatura">--°C</h2>
                                <span id="clima-condicao" aria-live="polite">Carregando clima...</span>
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
                            <small id="clima-atualizacao" aria-live="polite">Aguardando atualização...</small>
                            <button type="button" class="btn btn-link btn-sm p-0 d-none" id="clima-tentar-novamente">
                                Tentar novamente
                            </button>
                            <small>Dados meteorológicos: <a href="https://open-meteo.com/" target="_blank" rel="noopener noreferrer">Open-Meteo</a></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="banner-dashboard h-100 d-flex align-items-center">
                    <div class="banner-conteudo">
                        <h2 class="fw-bold ">FERRORAMAS</h2>
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

        <section class="row g-3 mb-3 align-items-stretch">
            <div class="col-lg-6 d-flex flex-column gap-3">

                <div class="card card-dashboard">
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

                <div class="card card-dashboard flex-grow-1">
                    <div class="card-body">
                        <h6 class="titulo-secao">Monitoramento ferroviário</h6>


                        <p class="texto-apoio">
                            Espaço reservado para exibição de alertas
                        </p>


                        <div class="grafico-area">
                            <canvas id="graficoTrens"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-dashboard h-100">
                    <div class="card-body d-flex flex-column">


                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="titulo-secao mb-1">Traçado ferroviário</h6>
                                <small class="texto-apoio">
                                    Visualização dos trilhos e movimentação do trem.
                                </small>
                            </div>


                            <span class="badge bg-success-subtle text-success">
                                Em operação
                            </span>
                        </div>


                        <div class="mapa-ferrovia flex-grow-1">
                            <!-- Espaço reservado para o mapa ferroviário -->
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script/clima.js"></script>
</body>

</html>