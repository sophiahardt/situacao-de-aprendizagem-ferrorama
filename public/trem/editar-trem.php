<?php
require_once "../../infra/conexao.php";

$mensagem = "";
$id_trem = $_GET["id"] ?? "";

if ($id_trem == "") {
    header("Location: visualizar-trem.php");
    exit;
}

$sql = "SELECT * FROM trem WHERE id_trem = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_trem);
$stmt->execute();
$resultado = $stmt->get_result();
$trem = $resultado->fetch_assoc();

if (!$trem) {
    header("Location: visualizar-trem.php");
    exit;
}

$sql = "SELECT id_rota FROM trem_rota WHERE id_trem = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_trem);
$stmt->execute();
$resultado = $stmt->get_result();
$rota_trem = $resultado->fetch_assoc();
$id_rota_trem = $rota_trem["id_rota"] ?? "";

$rotas = $conexao->query("SELECT id_rota, nome_rota FROM rota ORDER BY nome_rota");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_trem = $_POST["nome_trem"] ?? "";
    $velocidade_maxima = $_POST["velocidade_maxima"] ?? "";
    $tipo_trem = $_POST["tipo_trem"] ?? "";
    $id_rota = $_POST["id_rota"] ?? "";

    if ($nome_trem == "" || $velocidade_maxima == "" || $tipo_trem == "" || $id_rota == "") {
        $mensagem = "Preencha todos os campos.";
    } else {
        $sql = "UPDATE trem SET nome_trem = ?, velocidade_maxima = ?, tipo_trem = ? WHERE id_trem = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sdsi", $nome_trem, $velocidade_maxima, $tipo_trem, $id_trem);

        if ($stmt->execute()) {
            $sql_rota = "UPDATE trem_rota SET id_rota = ? WHERE id_trem = ?";
            $stmt_rota = $conexao->prepare($sql_rota);
            $stmt_rota->bind_param("ii", $id_rota, $id_trem);
            if ($stmt_rota->execute()) {
                header("Location: visualizar-trem.php");
                exit;
            } else {
                $mensagem = "Erro ao atualizar a rota do trem.";
            }
        } else {
            $mensagem = "Erro ao editar o trem.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição de Trens</title>
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

    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label"> ID do Trem </label>
                    <input type="text" class="form-control" value="<?= $trem["id_trem"] ?>" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label"> Nome do Trem </label>
                    <input type="text" name="nome_trem" class="form-control" value="<?= $trem["nome_trem"] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label"> Velocidade Máxima </label>
                    <input type="text" name="velocidade_maxima" class="form-control" value="<?= $trem["velocidade_maxima"] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label"> Tipo do Trem </label>
                    <input type="text" name="tipo_trem" class="form-control" value="<?= $trem["tipo_trem"] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label"> Rota </label>
                    <select name="id_rota" class="form-select" required>
                        <option selected disabled value=""> Selecione a rota em que o trem opera </option>

                        <?php while ($rota = $rotas->fetch_assoc()) { ?>
                            <option value="<?= $rota['id_rota'] ?>"
                                <?= $rota['id_rota'] == $id_rota_trem ? "selected" : "" ?>>
                                <?= $rota['nome_rota'] ?>
                            </option>
                        <?php } ?>

                    </select>
                </div>
                <button type="submit" class="btn btn-primary"> Salvar alterações </button>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>