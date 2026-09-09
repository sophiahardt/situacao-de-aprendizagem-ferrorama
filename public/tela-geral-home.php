<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashbord</title>
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
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="window.location.href='sensor/visualizar-sensor.php'">Sensores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="window.location.href='trem/visualizar-trem.php'">Trens</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="window.location.href='rota/visualizar-rota.php'">Rotas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="window.location.href='funcionario/visualizar-funcionario.php'">Funcionários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="window.location.href='relatorio/visualizar-relatorio.php'">Relatórios</a>
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

    <div class="mensagem-bem-vindo">
        <div class="d-flex align-items-center gap-2">
            <h2>👋 Bem-vindo,</h2>
            <h2 class="mensagem-admin-home">Administrador</h2>
        </div>
        <p class="pergunta-mensagem-bem-vindo">O que deseja fazer hoje?</p>
        <hr>
    </div>

    <div class="cards-informativo-cima">
        <div class="card-cima">
            <img src="../assets/img/card.trens.png" alt="">
        </div>
        <div class="card-cima">
            <img src="../assets/img/card.velocidade.png" alt="">
        </div>
        <div class="card-cima">
            <img src="../assets/img/card.energia.png" alt="">
        </div>
    </div>

    <div class="container-home">
        <div class="painel-esquerdo">
            <div class="botaos-home">
                <div class="p-3">
                    <button type="button" class="btn btn-primary btn-lg" onclick="window.location.href='sensor/cadastrar-sensor.php'">
                        <ion-icon name="add-outline"></ion-icon>
                        <div class="texto-botao">
                            <div class="titulo-botao-home">
                                Cadastrar Sensor
                            </div>
                            <div class="descricao-botao-home">
                                Adicionar novo sensor
                            </div>
                        </div>
                    </button>
                </div>

                <div class="p-3">
                    <button type="button" class="btn btn-success btn-lg">
                        <ion-icon name="train-outline"></ion-icon>
                        <div class="texto-botao">
                            <div class="titulo-botao-home">
                                Cadastrar Trem
                            </div>
                            <div class="descricao-botao-home">
                                Adicionar novo trem
                            </div>
                        </div>
                    </button>
                </div>

                <div class="p-3">
                    <button type="button" class="btn btn-lg text-white" style="background-color: purple;">
                        <ion-icon name="document-text-outline"></ion-icon>
                        <div class="texto-botao">
                            <div class="titulo-botao-home">
                                Ver Relatórios
                            </div>
                            <div class="descricao-botao-home">
                                Acessar relatórios do sistema
                            </div>
                        </div>
                    </button>
                </div>

                <div class="p-3">
                    <button type="button" class="btn btn-warning btn-lg text-white">
                        <ion-icon name="person-add-outline"></ion-icon>
                        <div class="texto-botao">
                            <div class="titulo-botao-home">
                                Cadastrar Usuários
                            </div>
                            <div class="descricao-botao-home">
                                Adicionar e gerenciar usuários
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>


        <div class="mapa-home">
            <img src="../assets/img/card.localizacao.png" alt="localização ">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>