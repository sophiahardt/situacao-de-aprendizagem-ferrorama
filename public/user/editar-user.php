
<?php
session_start();

require_once "../../infra/conexao.php";

$mensagem = "";
$tipo_mensagem = "";

$id_usuario = $_GET["id"] ?? "";

if ($id_usuario == "" || !is_numeric($id_usuario)) {
    header("Location: visualizar-user.php");
    exit;
}

$id_usuario = (int) $id_usuario;

$sql = "SELECT * FROM usuario WHERE id_usuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if (!$usuario) {
    header("Location: visualizar-user.php");
    exit;
}

$cargos = $conexao->query("SELECT id_cargo, nome_cargo FROM cargo");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $id_cargo = $_POST["id_cargo"] ?? "";
    $email = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");

    if ($nome == "" || $id_cargo == "" || $email == "" || $telefone == "") {
        $mensagem = "Preencha todos os campos.";
        $tipo_mensagem = "danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "Informe um e-mail válido.";
        $tipo_mensagem = "danger";
    } else {
        $sql = "UPDATE usuario
                SET nome = ?, id_cargo = ?, email = ?, telefone = ?
                WHERE id_usuario = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sissi", $nome, $id_cargo, $email, $telefone, $id_usuario);

        if ($stmt->execute()) {
            header("Location: visualizar-user.php");
            exit;
        } else {
            if ($conexao->errno == 1062) {
                $mensagem = "Este e-mail já está cadastrado.";
            } else {
                $mensagem = "Erro ao atualizar o usuário.";
            }

            $tipo_mensagem = "danger";
        }
    }

    $usuario["nome"] = $nome;
    $usuario["id_cargo"] = $id_cargo;
    $usuario["email"] = $email;
    $usuario["telefone"] = $telefone;
}
?>

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

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">

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

                    <li class="nav-item">
                        <a class="nav-link active" href="visualizar-user.php">Usuários</a>
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

    <div class="container mt-5">

        <div class="card shadow-sm">
            <div class="card-body">

                <h2 class="text-center mb-3">Editar Usuário</h2>

                <p class="text-center text-muted">
                    Altere os dados do usuário cadastrado.
                </p>

                <hr>

                <?php if ($mensagem != "") { ?>
                    <div class="alert alert-<?= $tipo_mensagem ?> text-center">
                        <?= htmlspecialchars($mensagem) ?>
                    </div>
                <?php } ?>

                <form method="POST" action="editar-user.php?id=<?= $id_usuario ?>" autocomplete="off">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>

                                <input type="text"
                                    id="nome"
                                    name="nome"
                                    class="form-control"
                                    placeholder="Digite o nome completo"
                                    value="<?= htmlspecialchars($usuario["nome"]) ?>"
                                    autocomplete="off"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_cargo" class="form-label">Cargo</label>

                                <select id="id_cargo" name="id_cargo" class="form-select" required>
                                    <option value="" disabled>
                                        Selecione o cargo
                                    </option>

                                    <?php while ($cargo = $cargos->fetch_assoc()) { ?>
                                        <option value="<?= $cargo["id_cargo"] ?>"
                                            <?= $usuario["id_cargo"] == $cargo["id_cargo"] ? "selected" : "" ?>>
                                            <?= htmlspecialchars($cargo["nome_cargo"]) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>

                                <input type="email"
                                    id="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Digite o e-mail"
                                    value="<?= htmlspecialchars($usuario["email"]) ?>"
                                    autocomplete="off"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>

                                <input type="tel"
                                    id="telefone"
                                    name="telefone"
                                    class="form-control"
                                    placeholder="(00) 00000-0000"
                                    value="<?= htmlspecialchars($usuario["telefone"]) ?>"
                                    autocomplete="off"
                                    required>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end">

                        <button type="button"
                            class="btn btn-light"
                            onclick="window.location.href='visualizar-user.php'">
                            <ion-icon name="close-outline"></ion-icon>
                            Cancelar
                        </button>

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