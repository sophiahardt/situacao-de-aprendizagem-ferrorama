<?php

require_once "../../infra/protecao.php";

verificarLogin();

require_once "../../infra/conexao.php";

$sql = "SELECT *
        FROM relatorio
        ORDER BY id_relatorio ASC";

$resultado = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Relatórios</title>
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
                        <a class="nav-link" href="../tela-geral-home.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../sensor/visualizar-sensor.php">Sensores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../trem/visualizar-trem.php">Trens</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../rota/visualizar-rota.php">Rotas</a>
                    </li>
<?php if (ehAdministrador()) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../user/visualizar-user.php">Usuários</a>
                    </li>
<?php } ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../relatorio/visualizar-relatorio.php">Relatórios</a>
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
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3">Relatórios gerados</h1>
                <p>Visualize relatórios gerados anteriormente</p>
            </div>
            <button class="btn btn-primary" style="background-color: #003399;"
                onclick="window.location.href='cadastrar-relatorio.php'">
                <ion-icon name="add-circle"></ion-icon>
                Gerar novo relatório
            </button>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nome do Relatório</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($relatorio = $resultado->fetch_assoc()) { ?>
                                <tr>
                                    <td>
                                        <?= $relatorio["nome_relatorio"] ?>
                                    </td>
                                    <td>
                                        <?= $relatorio["data_relatorio"] ?>
                                    </td>
                                    <td>
                                        <?php if (ehAdministrador()) { ?>
<a href="visualizar-relatorio-detalhes.php?id=<?= $relatorio["id_relatorio"] ?>"
                                            class="btn btn-primary btn-sm">
                                            <ion-icon name="eye"></ion-icon>
                                            Visualizar
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm">
                                            <ion-icon name="trash"></ion-icon>
                                            Excluir
                                        </button>
<?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>