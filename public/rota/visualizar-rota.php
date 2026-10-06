<?php
require_once "../../infra/protecao.php";
verificarLogin();
require_once "../../infra/conexao.php";
$sql = "SELECT id_rota, nome_rota, extensao, tempo_estimado_minutos
        FROM rota
        ORDER BY id_rota ASC";
$resultado = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Rotas</title>
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
                        <a class="nav-link active" href="../rota/visualizar-rota.php">Rotas</a>
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
                        <a class="btn btn-outline-light btn-sm d-flex align-items-center gap-2" href="../logout.php">
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
            <h1 class="h3">
                Lista de rotas cadastradas
            </h1>
            <?php if (ehAdministrador()) { ?>
                <button class="btn btn-primary"
                    style="background-color: #003399;"
                    onclick="window.location.href='cadastrar-rota.php'">
                    <ion-icon name="add-circle"></ion-icon>
                    Nova rota
                </button>
            <?php } ?>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Extensão</th>
                                <th>Tempo estimado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado && $resultado->num_rows > 0) { ?>
                                <?php while ($rota = $resultado->fetch_assoc()) { ?>
                                    <tr>
                                        <td>
                                            RTA-<?= str_pad($rota["id_rota"], 3, "0", STR_PAD_LEFT) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($rota["nome_rota"]) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($rota["extensao"]) ?> Km
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($rota["tempo_estimado_minutos"]) ?> min
                                        </td>
                                        <td>
                                            <?php if (ehAdministrador()) { ?>
<<<<<<< HEAD
                                                <a href="editar-rota.php?id=<?= $rota["id_rota"] ?>"
                                                    class="btn btn-warning btn-sm">
                                                    <ion-icon name="pencil"></ion-icon>
                                                    Editar
                                                </a>
                                                <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="abrirAviso(<?= $rota['id_rota'] ?>)">
                                                    <ion-icon name="trash"></ion-icon>
                                                    Excluir
                                                </button>
=======
                                            <a href="editar-rota.php?id=<?= $rota["id_rota"] ?>"
                                                class="btn btn-primary btn-sm">
                                                <ion-icon name="pencil"></ion-icon>
                                                Editar
                                            </a>
                                            <button type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="abrirAviso(<?= $rota['id_rota'] ?>)">
                                                <ion-icon name="trash"></ion-icon>
                                                Excluir
                                            </button>
>>>>>>> 8a7ea988163332526cf82682a76af0d78e474381
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="modalExclusao" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body p-4">
                    <div style="font-size: 40px;">⚠️</div>
                    <h4 class="fw-bold mt-2">
                        Deseja continuar?
                    </h4>
                    <p class="fw-bold mb-4">
                        Após a confirmação, não será possível reverter esta ação.
                    </p>
                    <div class="d-flex justify-content-center gap-5">
                        <button type="button" class="btn btn-secondary px-5" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <a id="btnConfirmarExclusao" href="#" class="btn btn-danger px-5">
                            Excluir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- fazer código em java script para abrir o aviso -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>