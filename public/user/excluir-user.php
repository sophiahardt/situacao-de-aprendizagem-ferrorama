<?php
session_start();
include_once("../../infra/conexao.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/login.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: visualizar-user.php");
    exit;
}

$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: visualizar-user.php?mensagem=Usuário excluído com sucesso");
        exit;
    }

    $erro = "Não foi possível excluir o usuário.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Excluir Funcionário</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../style.css">

</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../home/home.php">Dashboard</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link" href="../sensor/listar-sensor.php">Sensores</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../trem/listar-trem.php">Trens</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../rota/listar-rota.php">Rotas</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="visualizar-user.php">Funcionários</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../relatorio/listar-relatorio.php">Relatórios</a>
                    </li>

                    <li class="nav-item">
                        <span class="nav-link">
                            <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                        </span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../login/logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-sm border-0 p-4" style="max-width: 500px; width: 100%;">

        <div class="card-body text-center">

            <div class="mb-4">
                <span style="font-size: 55px;">⚠️</span>
            </div>

            <h2 class="mb-3">Deseja continuar?</h2>

            <p class="text-muted mb-4">
                Após a confirmação, não será possível reverter esta ação.
            </p>

            <?php if (isset($erro)) { ?>
                <div class="alert alert-danger">
                    <?php echo $erro; ?>
                </div>
            <?php } ?>

            <form method="POST">

                <div class="d-flex justify-content-center gap-3">

                    <a href="visualizar-user.php" class="btn btn-outline-secondary px-4">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-danger px-4">
                        Excluir
                    </button>

                </div>

            </form>

        </div>

    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>