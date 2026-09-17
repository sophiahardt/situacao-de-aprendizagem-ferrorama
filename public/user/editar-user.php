<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Usuário</title>

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
                        <a class="nav-link active" href="visualizar-user.html">
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

        <div class="card shadow-sm">

            <div class="card-body">

                <h2 class="text-center mb-3">
                    Editar Usuário
                </h2>

                <p class="text-center text-muted">
                    Altere os dados do Usuário cadastrado.
                </p>

                <hr>

                <form>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label for="nome" class="form-label">
                                Nome
                            </label>

                            <input type="text" id="nome" name="nome" class="form-control" required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label for="cargo" class="form-label">
                                Cargo
                            </label>

                            <select id="cargo" name="cargo" class="form-select" required>

                                <option value="" selected disabled>
                                    Selecione o cargo
                                </option>

                                <option value="administrador">
                                    Administrador
                                </option>

                                <option value="usuario">
                                    Usuário
                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label for="email" class="form-label">
                                E-mail
                            </label>

                            <input type="email" id="email" name="email" class="form-control" required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label for="telefone" class="form-label">
                                Telefone
                            </label>

                            <input type="tel" id="telefone" name="telefone" class="form-control" required>

                        </div>

                    </div>


                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                        <a href="visualizar-user.html" class="btn btn-light">

                            <ion-icon name="close-outline"></ion-icon>

                            Cancelar

                        </a>

                        <button type="submit" class="btn btn-primary">

                            <ion-icon name="save-outline"></ion-icon>

                            Salvar alterações

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>