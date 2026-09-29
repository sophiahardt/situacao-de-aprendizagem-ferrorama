<?php
require_once "../../infra/protecao.php";
verificarAdministrador();
require_once "../../infra/conexao.php";
$mensagem = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_relatorio = trim($_POST["nome_relatorio"] ?? "");
    $tipo_relatorio = $_POST["tipo_relatorio"] ?? "";
    $data_inicial = $_POST["data_inicial"] ?? "";
    $data_final = $_POST["data_final"] ?? "";
    if ($nome_relatorio == "" || $tipo_relatorio == "" || $data_inicial == "" || $data_final == "") {
        $mensagem = "Preencha todos os campos.";
    } else {
        $data_relatorio = date("Y-m-d");
        $sql = "INSERT INTO relatorio (nome_relatorio, data_relatorio, tipo_relatorio, data_inicial, data_final) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sssss", $nome_relatorio, $data_relatorio, $tipo_relatorio, $data_inicial, $data_final);
        if ($stmt->execute()) {
            header("Location: visualizar-relatorio.php");
            exit;
        } else {
            $mensagem = "Erro ao gerar o relatório.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Relatório</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../../style/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-sistema">
    <div class="container-fluid">
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation">
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
                    <a class="nav-link" href="../user/visualizar-user.php">
                        Usuários
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="visualizar-relatorio.php">
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
                    <a class="btn btn-outline-light btn-sm d-flex align-items-center gap-2"
                       href="../tela-login.php">
                        <ion-icon name="log-out-outline"></ion-icon>
                        <span>Sair</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-3">
    <div class="card">
        <div class="card-body p-5">
            <h2 class="text-center fw-bold mb-1">
                Gerar novo relatório
            </h2>
            <p class="text-center mb-2 fs-5">
                Informe os conteúdos do relatório
            </p>
            <hr class="mb-4">
            <?php if ($mensagem != "") { ?>
                <p class="text-center text-danger">
                    <?= htmlspecialchars($mensagem) ?>
                </p>
            <?php } ?>
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fs-5">
                                Nome
                            </label>
                            <input type="text"
                                   name="nome_relatorio"
                                   class="form-control"
                                   placeholder="Digite um nome para o relatório"
                                   required>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <label class="form-label fs-5">
                            Tipo de relatório
                        </label>
                        <div class="form-check mt-3">
                            <input class="form-check-input"
                                   type="radio"
                                   name="tipo_relatorio"
                                   id="tipo_trem"
                                   value="Trens"
                                   required>
                            <label class="form-check-label" for="tipo_trem">
                                Trens
                            </label>
                        </div>
                        <div class="form-check mt-3">
                            <input class="form-check-input"
                                   type="radio"
                                   name="tipo_relatorio"
                                   id="tipo_sensor"
                                   value="Sensores">
                            <label class="form-check-label" for="tipo_sensor">
                                Sensores
                            </label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fs-5">
                            Período
                        </label>
                        <div class="border border-dark rounded-3 p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="data_inicial" class="form-label">
                                        Data inicial
                                    </label>
                                    <input type="date"
                                           name="data_inicial"
                                           id="data_inicial"
                                           class="form-control"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="data_final" class="form-label">
                                        Data final
                                    </label>
                                    <input type="date"
                                           name="data_final"
                                           id="data_final"
                                           class="form-control"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-3 mt-5">
                    <button type="button"
                            class="btn btn-light px-4 py-2"
                            onclick="window.location.href='visualizar-relatorio.php'">
                        <ion-icon name="close-outline"></ion-icon>
                        Cancelar
                    </button>
                    <button type="submit"
                            class="btn btn-primary px-4 py-2">
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