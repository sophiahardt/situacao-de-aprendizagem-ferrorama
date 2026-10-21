<?php
session_start();
require_once "../../infra/conexao.php";
require_once "../../infra/protecao.php";
verificarAdministrador();

$mensagem = "";

$id_relatorio = $_GET["id"] ?? "";

if ($id_relatorio == "") {
    header("Location: visualizar-relatorio.php");
    exit;
}

$sql_busca = "SELECT * FROM relatorio WHERE id_relatorio = ?";
$stmt_busca = $conexao->prepare($sql_busca);
$stmt_busca->bind_param("i", $id_relatorio);
$stmt_busca->execute();
$resultado = $stmt_busca->get_result();
$relatorio = $resultado->fetch_assoc();

if (!$relatorio) {
    header("Location: visualizar-relatorio.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_relatorio = trim($_POST["nome_relatorio"] ?? "");
    $tipo_relatorio = $_POST["tipo_relatorio"] ?? "";
    $data_relatorio = $_POST["data_relatorio"] ?? "";

    if ($nome_relatorio == "" || $tipo_relatorio == "" || $data_relatorio == "") {
        $mensagem = "Preencha todos os campos.";
    } else {
        $sql = "UPDATE relatorio SET nome_relatorio = ?, tipo_relatorio = ?, data_relatorio = ? WHERE id_relatorio = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sssi", $nome_relatorio, $tipo_relatorio, $data_relatorio, $id_relatorio);

        if ($stmt->execute()) {
            header("Location: visualizar-relatorio.php");
            exit;
        } else {
            $mensagem = "Erro ao editar o relatório.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Relatório</title>
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
                    <a class="nav-link active" href="visualizar-relatorio.php">Relatórios</a>
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

<div class="container mt-5">

    <div class="card">

        <div class="card-body">

            <h2 class="text-center mb-3">
                Editar Relatório
            </h2>

            <p class="text-center text-muted">
                Altere as informações do relatório cadastrado no sistema
            </p>

            <hr>

            <?php if ($mensagem != "") { ?>
                <p class="text-center text-danger">
                    <?= htmlspecialchars($mensagem) ?>
                </p>
            <?php } ?>

            <form method="POST">

                <div class="mb-3">

                    <label class="form-label">
                        Nome do Relatório
                    </label>

                    <input type="text"
                        name="nome_relatorio"
                        class="form-control"
                        value="<?= htmlspecialchars($relatorio["nome_relatorio"]) ?>"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Tipo de Relatório
                    </label>

                    <select name="tipo_relatorio" class="form-select" required>

                        <option value="">Selecione o tipo</option>

                        <option value="Trens"
                            <?= $relatorio["tipo_relatorio"] == "Trens" ? "selected" : "" ?>>
                            Trens
                        </option>

                        <option value="Sensores"
                            <?= $relatorio["tipo_relatorio"] == "Sensores" ? "selected" : "" ?>>
                            Sensores
                        </option>

                    </select>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Data do Relatório
                    </label>

                    <input type="date"
                        name="data_relatorio"
                        class="form-control"
                        value="<?= htmlspecialchars($relatorio["data_relatorio"]) ?>"
                        required>

                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                    <button type="button"
                        class="btn btn-light"
                        onclick="window.location.href='visualizar-relatorio.php'">

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