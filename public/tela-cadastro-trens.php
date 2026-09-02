<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Trens</title>
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

        <div class="card">
            <div class="card-body">

                <h2 class="text-center mb-3">Cadastrar novo trem</h2>
                <p class="text-center text-muted">
                    Preencha as informações para cadastrar um novo trem no sistema
                </p>
                <hr>
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nome do Trem</label>
                                <input type="text" class="form-control" placeholder="Informe o nome do trem">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Velocidade Máxima</label>
                                <input type="text" class="form-control" placeholder="Informe a velocidade máxima do trem">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipo de Trem</label>
                                <select class="form-select">
                                    <option selected disabled>Selecione o tipo de trem</option>
                                    <option>Passageiro</option>
                                    <option>Carga</option>
                                    <option>Expresso</option>
                                </select>
                            </div>
                        </div>

                        

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <ion-icon name="save-outline"></ion-icon>
                            Salvar
                        </button>

                        <button type="button" class="btn btn-light">
                            <ion-icon name="close-outline"></ion-icon>
                            Cancelar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>


</body>

</html>