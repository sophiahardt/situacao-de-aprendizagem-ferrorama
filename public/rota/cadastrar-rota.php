<?php

require_once "../../infra/protecao.php";

verificarAdministrador();

require_once "../../infra/conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_rota = $_POST["nome_rota"] ?? "";
    $extensao = $_POST["extensao"] ?? "";
    $tempo_estimado = $_POST["tempo_estimado"] ?? "";

    if ($nome_rota == "" || $extensao == "" || $tempo_estimado == "") {
        $mensagem = "Preencha todos os campos.";
    } else {
        $sql = "INSERT INTO rota (nome_rota, extensao, tempo_estimado) 
                VALUES (?, ?, ?)";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sii", $nome_rota, $extensao, $tempo_estimado);

        if ($stmt->execute()) {
            header("Location: visualizar-rota.php");
            exit;
        } else {
            $mensagem = "Erro ao cadastrar a rota.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Rotas</title>
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
                    <a class="nav-link" href="visualizar-rota.php">Rotas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../user/visualizar-user.php">Usuários</a>
                </li>
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

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2 class="text-center mb-3">Cadastrar nova rota</h2>

            <p class="text-center text-muted">
                Preencha as informações para cadastrar uma nova rota no sistema
            </p>

            <hr>

            <?php if ($mensagem != "") { ?>
                <p class="text-center text-success">
                    <?= $mensagem ?>
                </p>
            <?php } ?>

            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nome da Rota</label>
                            <input type="text" name="nome_rota" class="form-control"
                                placeholder="Digite o nome" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Extensão (Km)</label>
                            <input type="number" name="extensao" class="form-control"
                                placeholder="Ex: 45" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tempo estimado (Min)</label>
                            <input type="number" name="tempo_estimado" class="form-control"
                                placeholder="Ex: 45" required>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-light"
                        onclick="window.location.href='visualizar-rota.php'">
                        <ion-icon name="close-outline"></ion-icon>
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <ion-icon name="save-outline"></ion-icon>
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../script/validacao.js"></script>

</body>
</html>