<?php

require_once "../../infra/protecao.php";

verificarAdministrador();

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: visualizar-user.php");
    exit;
}

$id = (int) $_GET["id"];
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $sql = "DELETE FROM usuario WHERE id_usuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: visualizar-user.php");
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

    <title>Excluir Usuário</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../style/style.css">

</head>

<body>

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

                <?php if ($erro != "") { ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($erro) ?>
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

</body>
</html>