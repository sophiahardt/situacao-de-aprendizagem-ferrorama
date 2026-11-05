
<?php

require_once "../../infra/protecao.php";
verificarAdministrador();

require_once "../../infra/conexao.php";

$mensagem = "";
$tipo_mensagem = "";

$cargos = $conexao->query("SELECT id_cargo, nome_cargo FROM cargo");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $id_cargo = $_POST["id_cargo"] ?? "";
    $email = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");
    $cep_digitado = trim($_POST["cep"] ?? "");
    $logradouro = trim($_POST["logradouro"] ?? "");
    $bairro = trim($_POST["bairro"] ?? "");
    $cidade = trim($_POST["cidade"] ?? "");
    $uf = strtoupper(trim($_POST["uf"] ?? ""));
    $senha = $_POST["senha"] ?? "";

    $cep = null;

    if ($cep_digitado !== "") {

        if (!preg_match('/^\d{5}-?\d{3}$/', $cep_digitado)) {
            $mensagem = "Informe um CEP válido com 8 dígitos.";
            $tipo_mensagem = "danger";
        } else {
            $cep_numerico = str_replace("-", "", $cep_digitado);
            $cep = substr($cep_numerico, 0, 5) . "-" . substr($cep_numerico, 5);
        }
    }

    if ($uf !== "" && !preg_match('/^[A-Z]{2}$/', $uf)) {
        $mensagem = "Informe uma UF válida com 2 letras.";
        $tipo_mensagem = "danger";
    }

    $telefone_numerico = preg_replace('/\D/', '', $telefone);

    if (!preg_match('/^(?:55)?\d{11}$/', $telefone_numerico)) {
        $mensagem = "Informe um telefone brasileiro válido com DDD e número completo.";
        $tipo_mensagem = "danger";
    } else {
        $telefone = strlen($telefone_numerico) === 13
            ? "+55 (" . substr($telefone_numerico, 2, 2) . ") " . substr($telefone_numerico, 4, 5) . "-" . substr($telefone_numerico, 9, 4)
            : "+55 (" . substr($telefone_numerico, 0, 2) . ") " . substr($telefone_numerico, 2, 5) . "-" . substr($telefone_numerico, 7, 4);
    }

    if ($nome == "" || $id_cargo == "" || $email == "" || $telefone == "" || $senha == "") {
        $mensagem = "Preencha todos os campos.";
        $tipo_mensagem = "danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "Informe um e-mail válido.";
        $tipo_mensagem = "danger";
    } elseif ($tipo_mensagem === "") {

        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (id_cargo, nome, email, telefone, cep, logradouro, bairro, cidade, uf, senha_hash)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        $tipos = "i" . str_repeat("s", 9);

        $stmt->bind_param(
            $tipos,
            $id_cargo,
            $nome,
            $email,
            $telefone,
            $cep,
            $logradouro,
            $bairro,
            $cidade,
            $uf,
            $senha_hash
        );

        if ($stmt->execute()) {
            header("Location: visualizar-user.php?sucesso=cadastrado");
            exit;
        } else {

            if ($stmt->errno == 1062) {
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

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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

    <div class="container mt-5">

        <div class="card">
            <div class="card-body">

                <h2 class="text-center mb-3">Cadastrar novo usuário</h2>

                <p class="text-center text-muted">
                    Preencha os dados abaixo para cadastrar um novo usuário.
                </p>

                <hr>

                <?php if ($mensagem != "") { ?>
                    <div class="alert alert-<?= htmlspecialchars($tipo_mensagem) ?> text-center">
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
                                    value="<?= htmlspecialchars($_POST["nome"] ?? "") ?>"
                                    autocomplete="off"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>

                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Digite o e-mail"
                                    value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"
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
                                        <option value="<?= $cargo['id_cargo'] ?>"
                                            <?= ($_POST["id_cargo"] ?? "") == $cargo["id_cargo"] ? "selected" : "" ?>>

                                            <?= htmlspecialchars($cargo['nome_cargo']) ?>

                                        </option>
                                    <?php } ?>

                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Telefone</label>

                               <input type="tel"
                                id="telefone"
                                name="telefone"
                                class="form-control"
                                placeholder="+55 (47) 99999-9999"
                                value="<?= htmlspecialchars($_POST["telefone"] ?? "") ?>"
                                autocomplete="off"
                                maxlength="19"
                                required>
                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <div class="mb-3">

                                <label for="cep" class="form-label">CEP</label>

                                <div class="input-group">

                                    <input type="text"
                                        id="cep"
                                        name="cep"
                                        class="form-control"
                                        placeholder="00000-000"
                                        value="<?= htmlspecialchars($_POST["cep"] ?? "") ?>"
                                        inputmode="numeric"
                                        autocomplete="postal-code"
                                        maxlength="9"
                                        pattern="\d{5}-?\d{3}"
                                        aria-describedby="cep-ajuda cep-mensagem">

                                    <button type="button" class="btn btn-outline-secondary" id="consultar-cep">
                                        Buscar CEP
                                    </button>

                                </div>

                                <small id="cep-ajuda" class="text-muted">
                                    Informe o CEP para preencher o endereço; os campos podem ser editados.
                                </small>

                                <div id="cep-mensagem" class="small mt-1" role="status" aria-live="polite"></div>

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="logradouro" class="form-label">Logradouro</label>

                            <input type="text"
                                id="logradouro"
                                name="logradouro"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST["logradouro"] ?? "") ?>"
                                autocomplete="address-line1">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="bairro" class="form-label">Bairro</label>

                            <input type="text"
                                id="bairro"
                                name="bairro"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST["bairro"] ?? "") ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cidade" class="form-label">Cidade</label>

                            <input type="text"
                                id="cidade"
                                name="cidade"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST["cidade"] ?? "") ?>"
                                autocomplete="address-level2">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="uf" class="form-label">UF</label>

                            <input type="text"
                                id="uf"
                                name="uf"
                                class="form-control"
                                maxlength="2"
                                value="<?= htmlspecialchars($_POST["uf"] ?? "") ?>"
                                autocomplete="address-level1"
                                pattern="[A-Za-z]{2}">
                        </div>

                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                        <button type="button"
                            class="btn btn-light"
                            onclick="window.location.href='visualizar-user.php'">

                            <ion-icon name="close-outline"></ion-icon>
                            Cancelar

                        </button>

                        <button type="submit" class="btn btn-primary btn-login px-5">

                            <ion-icon name="save-outline"></ion-icon>
                            Salvar

                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../../script/consulta-cep.js"></script>

    <script>
        function formatarTelefone(telefone) {
            const digits = telefone.replace(/\D/g, "").slice(0, 13);

            if (!digits) {
                return "";
            }

            let numero = digits;

            if (numero.startsWith("55")) {
                numero = numero.slice(2);
            }

            if (numero.length <= 2) {
                return `+55 (${numero}`;
            }

            const ddd = numero.slice(0, 2);
            const restante = numero.slice(2);

            if (restante.length <= 5) {
                return `+55 (${ddd}) ${restante}`;
            }

            if (restante.length <= 9) {
                return `+55 (${ddd}) ${restante.slice(0, 5)}-${restante.slice(5)}`;
            }

            return `+55 (${ddd}) ${restante.slice(0, 5)}-${restante.slice(5, 9)}`;
        }

        document.addEventListener("DOMContentLoaded", function() {
            const telefone = document.getElementById("telefone");

            if (telefone) {
                telefone.addEventListener("input", function() {
                    this.value = formatarTelefone(this.value);
                });
            }
        });
    </script>

</body>

</html>
