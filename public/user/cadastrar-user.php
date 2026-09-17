<?php
session_start();

require_once "../../infra/conexao.php";

$mensagem = "";
$tipo_mensagem = "";

$cargos = $conexao->query("SELECT id_cargo, nome_cargo FROM cargo");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $id_cargo = $_POST["id_cargo"] ?? "";
    $email = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if ($nome == "" || $id_cargo == "" || $email == "" || $telefone == "" || $senha == "") {
        $mensagem = "Preencha todos os campos.";
        $tipo_mensagem = "danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "Informe um e-mail válido.";
        $tipo_mensagem = "danger";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (id_cargo, nome, email, telefone, senha_hash)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("issss", $id_cargo, $nome, $email, $telefone, $senha_hash);

        if ($stmt->execute()) {
            $mensagem = "Usuário cadastrado com sucesso!";
            $tipo_mensagem = "success";
        } else {
            if ($conexao->errno == 1062) {
                $mensagem = "Este e-mail já está cadastrado.";
            } else {
                $mensagem = "Erro ao cadastrar o usuário.";
            }

            $tipo_mensagem = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Usuários</title>

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
                            href="#">
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

                <h2 class="text-center mb-3">Cadastrar novo usuário</h2>

                <p class="text-center text-muted">
                    Preencha os dados abaixo para cadastrar um novo usuário.
                </p>

                <hr>

                <?php if ($mensagem != "") { ?>
                    <div class="alert alert-<?= $tipo_mensagem ?> text-center">
                        <?= htmlspecialchars($mensagem) ?>
                    </div>
                <?php } ?>

                <form method="POST" autocomplete="off">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label">Nome Completo</label>

                                <input type="text"
                                    name="nome"
                                    class="form-control"
                                    placeholder="Digite o nome completo"
                                    autocomplete="off"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>

                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Digite o e-mail"
                                    autocomplete="off"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Senha</label>

                                <input type="password"
                                    name="senha"
                                    class="form-control"
                                    placeholder="Digite a senha"
                                    autocomplete="new-password"
                                    required>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label">Cargo</label>

                                <select name="id_cargo" class="form-select" required>
                                    <option selected disabled value="">
                                        Selecione o cargo
                                    </option>

                                    <?php while ($cargo = $cargos->fetch_assoc()) { ?>
                                        <option value="<?= $cargo['id_cargo'] ?>">
                                            <?= htmlspecialchars($cargo['nome_cargo']) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Telefone</label>

                                <input type="tel"
                                    name="telefone"
                                    class="form-control"
                                    placeholder="(00) 00000-0000"
                                    autocomplete="off"
                                    required>
                            </div>

                        </div>

                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                        <button type="button"
                            class="btn btn-light"
                            onclick="window.location.href='visualizar-user.php'">
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

</body>

</html>