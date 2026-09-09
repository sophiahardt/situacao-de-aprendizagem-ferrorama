<?php
require_once "../../infra/conexao.php";

$sql = "SELECT trem.*,
               rota.nome_rota
        FROM trem
        LEFT JOIN trem_rota ON trem.id_trem = trem_rota.id_trem
        LEFT JOIN rota ON trem_rota.id_rota = rota.id_rota";

$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista trens cadastrados</title>
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
                        <a class="nav-link" href="visualizar-trem.php">Trens</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../rota/visualizar-rota.php">Rotas</a>
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

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Lista de trens cadastrados</h1>

            <button class="btn btn-primary" style="background-color: #003399;" onclick="window.location.href='cadastrar-trem.php'">
                <ion-icon name="add-circle"></ion-icon>
                Novo trem
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Velocidade Máxima</th>
                                <th>Tipo</th>
                                <th>Rotas</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($trem = $resultado->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $trem["id_trem"] ?></td>
                                    <td><?= $trem["nome_trem"] ?></td>
                                    <td><?= $trem["velocidade_maxima"] ?></td>
                                    <td><?= $trem["tipo_trem"] ?></td>
                                    <td><?= $trem["nome_rota"] ?></td>

                                    <td>
                                        <?php
                                        if ($trem["tipo_trem"] == "Velocidade") {
                                            echo '<ion-icon name="speedometer"></ion-icon>';
                                        }

                                        if ($trem["tipo_trem"] == "Temperatura") {
                                            echo '<ion-icon name="thermometer"></ion-icon>';
                                        }

                                        if ($trem["tipo_trem"] == "Presença") {
                                            echo '<ion-icon name="person"></ion-icon>';
                                        }
                                        ?>

                                        <?= $trem["tipo_trem"] ?>
                                    </td>

                                    <td>
                                        <a href="visualizar-sensor.php?id=<?= $trem["id_trem"] ?>"
                                            class="btn btn-info btn-sm text-white">
                                            <ion-icon name="eye"></ion-icon>
                                            Visualizar
                                        </a>

                                        <a href="editar-sensor.php?id=<?= $trem["id_trem"] ?>"
                                            class="btn btn-warning btn-sm">
                                            <ion-icon name="pencil"></ion-icon>
                                            Editar
                                        </a>

                                        <a href="excluir-sensor.php?id=<?= $trem["id_trem"] ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Tem certeza que deseja excluir este trem?');">
                                            <ion-icon name="trash"></ion-icon>
                                            Excluir
                                        </a>
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