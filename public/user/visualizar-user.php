<?php
require_once "../../infra/protecao.php";
verificarAdministrador();
require_once "../../infra/conexao.php";

$total_colunas = ehAdministrador() ? 7 : 6;

function formatarTelefone($telefone)
{
    $numeros = preg_replace('/\D/', '', $telefone);

    if (!preg_match('/^(?:55)?\d{11}$/', $numeros)) {
        return "";
    }

    if (strlen($numeros) === 13) {
        $numeros = substr($numeros, 2);
    }

    return "+55 (" . substr($numeros, 0, 2) . ") " . substr($numeros, 2, 5) . "-" . substr($numeros, 7, 4);
}

function formatarCep($cep)
{
    if ($cep === "") {
        return null;
    }

    $numeros = str_replace("-", "", $cep);

    return substr($numeros, 0, 5) . "-" . substr($numeros, 5);
}

function validarUsuario($nome, $id_cargo, $email, $telefone, $cep, $uf, $senha, $senha_obrigatoria)
{
    if ($nome == "" || $id_cargo == "" || $email == "" || $telefone == "" || ($senha_obrigatoria && $senha == "")) {
        return "Preencha todos os campos.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Informe um e-mail válido.";
    }

    if (formatarTelefone($telefone) == "") {
        return "Informe um telefone brasileiro válido com DDD e número completo.";
    }

    if ($cep !== "" && !preg_match('/^\d{5}-?\d{3}$/', $cep)) {
        return "Informe um CEP válido com 8 dígitos.";
    }

    if ($uf !== "" && !preg_match('/^[A-Z]{2}$/', $uf)) {
        return "Informe uma UF válida com 2 letras.";
    }

    return "";
}

$mensagem = "";
$tipo_mensagem = "success";
$abrir_modal = false;
$abrir_modal_edicao = false;

$nome_form = "";
$id_cargo_form = "";
$email_form = "";
$telefone_form = "";
$cep_form = "";
$logradouro_form = "";
$bairro_form = "";
$cidade_form = "";
$uf_form = "";

$id_usuario_edicao = "";
$nome_edicao = "";
$id_cargo_edicao = "";
$email_edicao = "";
$telefone_edicao = "";
$cep_edicao = "";
$logradouro_edicao = "";
$bairro_edicao = "";
$cidade_edicao = "";
$uf_edicao = "";

$sucesso = $_GET["sucesso"] ?? "";
$erro = $_GET["erro"] ?? "";

if ($sucesso == "cadastrado") {
    $mensagem = "Usuário cadastrado com sucesso!";
} elseif ($sucesso == "editado") {
    $mensagem = "Usuário editado com sucesso!";
} elseif ($sucesso == "1") {
    $mensagem = "Usuário excluído com sucesso!";
} elseif ($erro == "trens") {
    $mensagem = "Não é possível excluir este usuário, pois ele está associado a um ou mais trens.";
    $tipo_mensagem = "danger";
} elseif ($erro == "ultimo_admin") {
    $mensagem = "Não é possível excluir o único administrador do sistema.";
    $tipo_mensagem = "danger";
} elseif ($erro == "exclusao") {
    $mensagem = "Erro ao excluir o usuário.";
    $tipo_mensagem = "danger";
}

$consulta_cargos = $conexao->query("SELECT id_cargo, nome_cargo FROM cargo ORDER BY nome_cargo");
$cargos = $consulta_cargos ? $consulta_cargos->fetch_all(MYSQLI_ASSOC) : [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (($_POST["acao"] ?? "") == "cadastrar") {
        $nome_form = trim($_POST["nome"] ?? "");
        $id_cargo_form = $_POST["id_cargo"] ?? "";
        $email_form = trim($_POST["email"] ?? "");
        $telefone_form = trim($_POST["telefone"] ?? "");
        $cep_form = trim($_POST["cep"] ?? "");
        $logradouro_form = trim($_POST["logradouro"] ?? "");
        $bairro_form = trim($_POST["bairro"] ?? "");
        $cidade_form = trim($_POST["cidade"] ?? "");
        $uf_form = strtoupper(trim($_POST["uf"] ?? ""));
        $senha = $_POST["senha"] ?? "";

        $abrir_modal = true;

        if (!ehAdministrador()) {
            $mensagem = "Você não tem permissão para cadastrar usuários.";
        } else {
            $mensagem = validarUsuario($nome_form, $id_cargo_form, $email_form, $telefone_form, $cep_form, $uf_form, $senha, true);
        }

        if ($mensagem == "") {
            $id_cargo = (int) $id_cargo_form;
            $telefone = formatarTelefone($telefone_form);
            $cep = formatarCep($cep_form);
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuario
                    (id_cargo, nome, email, telefone, cep, logradouro, bairro, cidade, uf, senha_hash)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conexao->prepare($sql);

            $stmt->bind_param(
                "isssssssss",
                $id_cargo,
                $nome_form,
                $email_form,
                $telefone,
                $cep,
                $logradouro_form,
                $bairro_form,
                $cidade_form,
                $uf_form,
                $senha_hash
            );

            try {
                if ($stmt->execute()) {
                    header("Location: visualizar-user.php?sucesso=cadastrado");
                    exit;
                }

                $mensagem = "Erro ao cadastrar o usuário.";
            } catch (mysqli_sql_exception $excecao) {
                $mensagem = $excecao->getCode() == 1062
                    ? "Este e-mail já está cadastrado."
                    : "Erro ao cadastrar o usuário.";
            }
        }

        $tipo_mensagem = "danger";
    } elseif (($_POST["acao"] ?? "") == "editar") {
        $id_usuario_edicao = (int) ($_POST["id_usuario"] ?? 0);
        $nome_edicao = trim($_POST["nome"] ?? "");
        $id_cargo_edicao = $_POST["id_cargo"] ?? "";
        $email_edicao = trim($_POST["email"] ?? "");
        $telefone_edicao = trim($_POST["telefone"] ?? "");
        $cep_edicao = trim($_POST["cep"] ?? "");
        $logradouro_edicao = trim($_POST["logradouro"] ?? "");
        $bairro_edicao = trim($_POST["bairro"] ?? "");
        $cidade_edicao = trim($_POST["cidade"] ?? "");
        $uf_edicao = strtoupper(trim($_POST["uf"] ?? ""));
        $nova_senha = $_POST["nova_senha"] ?? "";

        $abrir_modal_edicao = true;

        if (!ehAdministrador()) {
            $mensagem = "Você não tem permissão para editar usuários.";
        } elseif ($id_usuario_edicao <= 0) {
            $mensagem = "Preencha todos os campos.";
        } else {
            $mensagem = validarUsuario($nome_edicao, $id_cargo_edicao, $email_edicao, $telefone_edicao, $cep_edicao, $uf_edicao, $nova_senha, false);
        }

        if ($mensagem == "") {
            $id_cargo = (int) $id_cargo_edicao;
            $telefone = formatarTelefone($telefone_edicao);
            $cep = formatarCep($cep_edicao);

            if ($nova_senha != "") {
                $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

                $sql = "UPDATE usuario
                        SET nome = ?, id_cargo = ?, email = ?, telefone = ?, cep = ?,
                            logradouro = ?, bairro = ?, cidade = ?, uf = ?, senha_hash = ?
                        WHERE id_usuario = ?";

                $stmt = $conexao->prepare($sql);

                $stmt->bind_param(
                    "sissssssssi",
                    $nome_edicao,
                    $id_cargo,
                    $email_edicao,
                    $telefone,
                    $cep,
                    $logradouro_edicao,
                    $bairro_edicao,
                    $cidade_edicao,
                    $uf_edicao,
                    $senha_hash,
                    $id_usuario_edicao
                );
            } else {
                $sql = "UPDATE usuario
                        SET nome = ?, id_cargo = ?, email = ?, telefone = ?, cep = ?,
                            logradouro = ?, bairro = ?, cidade = ?, uf = ?
                        WHERE id_usuario = ?";

                $stmt = $conexao->prepare($sql);

                $stmt->bind_param(
                    "sisssssssi",
                    $nome_edicao,
                    $id_cargo,
                    $email_edicao,
                    $telefone,
                    $cep,
                    $logradouro_edicao,
                    $bairro_edicao,
                    $cidade_edicao,
                    $uf_edicao,
                    $id_usuario_edicao
                );
            }

            try {
                if ($stmt->execute()) {
                    header("Location: visualizar-user.php?sucesso=editado");
                    exit;
                }

                $mensagem = "Erro ao atualizar o usuário.";
            } catch (mysqli_sql_exception $excecao) {
                $mensagem = $excecao->getCode() == 1062
                    ? "Este e-mail já está cadastrado."
                    : "Erro ao atualizar o usuário.";
            }
        }

        $tipo_mensagem = "danger";
    }
}

$sql = "SELECT usuario.*,
               cargo.nome_cargo
        FROM usuario
        INNER JOIN cargo ON usuario.id_cargo = cargo.id_cargo";

$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários cadastrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../../style/style.css?v=5">
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
    <div class="container-fluid p-4 crud-container">
        <div class="d-flex justify-content-between align-items-center mb-4 crud-cabecalho">
            <h1 class="h3">
                Lista de usuários cadastrados
            </h1>
            <?php if (ehAdministrador()) { ?>
                <button type="button" class="btn btn-sistema"
                    data-bs-toggle="modal" data-bs-target="#modalCadastroUsuario">
                    <ion-icon name="add-circle"></ion-icon>
                    Novo usuário
                </button>
            <?php } ?>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Cargo</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>CEP</th>
                                <th>Endereço</th>
                                <?php if (ehAdministrador()) { ?>
                                    <th>Ações</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado && $resultado->num_rows > 0) { ?>
                                <?php while ($usuario = $resultado->fetch_assoc()) { ?>
                                    <tr>
                                        <td>
                                            <?= htmlspecialchars($usuario["nome"]) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($usuario["nome_cargo"]) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($usuario["email"]) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($usuario["telefone"] ?? "") ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($usuario["cep"] ?? "") ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars(implode(", ", array_filter([
                                                $usuario["logradouro"],
                                                $usuario["bairro"],
                                                $usuario["cidade"],
                                                $usuario["uf"]
                                            ], static fn($valor) => $valor !== null && $valor !== ""))) ?>
                                        </td>
                                        <?php if (ehAdministrador()) { ?>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-editar btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditarUsuario"
                                                    data-id="<?= $usuario["id_usuario"] ?>"
                                                    data-nome="<?= htmlspecialchars($usuario["nome"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-cargo="<?= $usuario["id_cargo"] ?>"
                                                    data-email="<?= htmlspecialchars($usuario["email"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-telefone="<?= htmlspecialchars($usuario["telefone"] ?? "", ENT_QUOTES, 'UTF-8') ?>"
                                                    data-cep="<?= htmlspecialchars($usuario["cep"] ?? "", ENT_QUOTES, 'UTF-8') ?>"
                                                    data-logradouro="<?= htmlspecialchars($usuario["logradouro"] ?? "", ENT_QUOTES, 'UTF-8') ?>"
                                                    data-bairro="<?= htmlspecialchars($usuario["bairro"] ?? "", ENT_QUOTES, 'UTF-8') ?>"
                                                    data-cidade="<?= htmlspecialchars($usuario["cidade"] ?? "", ENT_QUOTES, 'UTF-8') ?>"
                                                    data-uf="<?= htmlspecialchars($usuario["uf"] ?? "", ENT_QUOTES, 'UTF-8') ?>">
                                                    <ion-icon name="pencil"></ion-icon>
                                                    Editar
                                                </button>
                                                <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="abrirAviso(<?= $usuario['id_usuario'] ?>, 'excluir-user.php')">
                                                    <ion-icon name="trash"></ion-icon>
                                                    Excluir
                                                </button>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="<?= $total_colunas ?>" class="text-center text-muted">
                                        Nenhum usuário cadastrado.
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MENSAGEM DE SUCESSO OU ERRO -->
        <?php if ($mensagem != "" && !$abrir_modal && !$abrir_modal_edicao) { ?>
            <div class="alert alert-<?= $tipo_mensagem ?> alert-dismissible fade show mt-3" role="alert">
                <?= htmlspecialchars($mensagem) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Fechar"></button>
            </div>
        <?php } ?>
    </div>

    <?php
    define("USUARIO_MODAIS", true);
    require __DIR__ . "/cadastrar-user.php";
    require __DIR__ . "/editar-user.php";
    require __DIR__ . "/excluir-user.php";
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script/crud.js"></script>
    <script src="../../script/usuario.js"></script>

    <!-- MODAIS DE CADASTRO E EDIÇÃO -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if ($abrir_modal) { ?>
                let modalCadastro = new bootstrap.Modal(
                    document.getElementById("modalCadastroUsuario")
                );
                modalCadastro.show();
            <?php } ?>

            <?php if ($abrir_modal_edicao) { ?>
                document.getElementById("editar_id_usuario").value = <?= json_encode($id_usuario_edicao) ?>;
                document.getElementById("editar_nome").value = <?= json_encode($nome_edicao) ?>;
                document.getElementById("editar_id_cargo").value = <?= json_encode($id_cargo_edicao) ?>;
                document.getElementById("editar_email").value = <?= json_encode($email_edicao) ?>;
                document.getElementById("editar_telefone").value = <?= json_encode($telefone_edicao) ?>;
                document.getElementById("editar_cep").value = <?= json_encode($cep_edicao) ?>;
                document.getElementById("editar_logradouro").value = <?= json_encode($logradouro_edicao) ?>;
                document.getElementById("editar_bairro").value = <?= json_encode($bairro_edicao) ?>;
                document.getElementById("editar_cidade").value = <?= json_encode($cidade_edicao) ?>;
                document.getElementById("editar_uf").value = <?= json_encode($uf_edicao) ?>;

                let modalEdicao = new bootstrap.Modal(
                    document.getElementById("modalEditarUsuario")
                );
                modalEdicao.show();
            <?php } ?>
        });
    </script>
</body>

</html>