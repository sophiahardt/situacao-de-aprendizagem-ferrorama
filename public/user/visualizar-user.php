<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de funcionários cadastrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body class="tela-funcionarios">

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
                        <a class="nav-link" href="#">Sensores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Trens</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Rotas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Funcionários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Relatórios</a>
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

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h1 class="titulo">Funcionários cadastrados</h1>
                <p class="subtitulo">Visualize, cadastre ou remova funcionários cadastrados</p>
            </div>

            <button class="btn-cadastrar">
                + Cadastrar usuário
            </button>
        </div>

        <hr>

        <div class="card-funcionarios mt-4">
            <div class="row tabela-topo text-center">
                <div class="col">Nome</div>
                <div class="col">Cargo</div>
                <div class="col">Email</div>
                <div class="col">Telefone</div>
                <div class="col"></div>
            </div>

            <div class="row linha-funcionario align-items-center text-center">
                <div class="col">João da Silva</div>
                <div class="col">Administrador</div>
                <div class="col">joao@email.com</div>
                <div class="col">(11) 98765-4321</div>

                <div class="col">
                    <button class="btn-excluir">
                        🗑
                    </button>
                </div>

            </div>
            <div class="row linha-funcionario align-items-center text-center">

                <div class="col">Maria Oliveira</div>
                <div class="col">Funcionário</div>
                <div class="col">maria@email.com</div>
                <div class="col">(11) 97654-3210</div>

                <div class="col">
                    <button class="btn-excluir">
                        🗑
                    </button>
                </div>

            </div>
            <div class="row linha-funcionario align-items-center text-center">

                <div class="col">Carlos Mendes</div>
                <div class="col">Funcionário</div>
                <div class="col">carlos@email.com</div>
                <div class="col">(11) 99876-1234</div>

                <div class="col">
                    <button class="btn-excluir">
                        🗑
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>