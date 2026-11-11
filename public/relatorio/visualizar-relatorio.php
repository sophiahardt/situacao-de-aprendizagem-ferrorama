<?php
session_start();
require_once "../../infra/conexao.php";
require_once "../../infra/protecao.php";
verificarLogin();
$mensagem = "";
$tipo_mensagem = "success";
$abrir_modal = ($_GET["abrir"] ?? "") == "cadastro";
$abrir_modal_edicao = false;
$nome_relatorio_form = "";
$tipo_relatorio_form = "";
$data_relatorio_form = "";
$id_relatorio_edicao = "";
$nome_relatorio_edicao = "";
$tipo_relatorio_edicao = "";
$data_relatorio_edicao = "";

if (isset($_GET["editar"]) && ehAdministrador()) {
    $id_relatorio_edicao = filter_var($_GET["editar"], FILTER_VALIDATE_INT);
    if (!$id_relatorio_edicao || $id_relatorio_edicao <= 0) {
        header("Location: visualizar-relatorio.php");
        exit;
    }

    $stmt_edicao = $conexao->prepare("SELECT nome_relatorio, tipo_relatorio, data_relatorio FROM relatorio WHERE id_relatorio = ?");
    $stmt_edicao->bind_param("i", $id_relatorio_edicao);
    $stmt_edicao->execute();
    $relatorio_edicao = $stmt_edicao->get_result()->fetch_assoc();

    if (!$relatorio_edicao) {
        header("Location: visualizar-relatorio.php");
        exit;
    }

    $nome_relatorio_edicao = $relatorio_edicao["nome_relatorio"];
    $tipo_relatorio_edicao = $relatorio_edicao["tipo_relatorio"];
    $data_relatorio_edicao = $relatorio_edicao["data_relatorio"];
    $abrir_modal_edicao = true;
}

if (($_GET["sucesso"] ?? "") == "cadastrado") {
    $mensagem = "Relatório cadastrado com sucesso!";
} elseif (($_GET["sucesso"] ?? "") == "editado") {
    $mensagem = "Relatório editado com sucesso!";
} elseif (($_GET["sucesso"] ?? "") == "1") {
    $mensagem = "Relatório excluído com sucesso!";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = $_POST["acao"] ?? "";

    if ($acao == "cadastrar") {
        $nome_relatorio_form = trim($_POST["nome_relatorio"] ?? "");
        $tipo_relatorio_form = $_POST["tipo_relatorio"] ?? "";
        $data_relatorio_form = $_POST["data_relatorio"] ?? "";
        $abrir_modal = true;

        if ($nome_relatorio_form == "" || $tipo_relatorio_form == "" || $data_relatorio_form == "") {
            $mensagem = "Preencha todos os campos.";
        } else {
            $sql_cadastro = "INSERT INTO relatorio (nome_relatorio, tipo_relatorio, data_relatorio) VALUES (?, ?, ?)";
            $stmt_cadastro = $conexao->prepare($sql_cadastro);
            $stmt_cadastro->bind_param("sss", $nome_relatorio_form, $tipo_relatorio_form, $data_relatorio_form);

            if ($stmt_cadastro->execute()) {
                header("Location: visualizar-relatorio.php?sucesso=cadastrado");
                exit;
            }

            $mensagem = "Erro ao gerar o relatório.";
        }

        $tipo_mensagem = "danger";
    } elseif ($acao == "editar") {
        $id_relatorio_edicao = (int) ($_POST["id_relatorio"] ?? 0);
        $nome_relatorio_edicao = trim($_POST["nome_relatorio"] ?? "");
        $tipo_relatorio_edicao = $_POST["tipo_relatorio"] ?? "";
        $data_relatorio_edicao = $_POST["data_relatorio"] ?? "";
        $abrir_modal_edicao = true;

        if (!ehAdministrador()) {
            $mensagem = "Você não tem permissão para editar relatórios.";
        } elseif ($id_relatorio_edicao <= 0 || $nome_relatorio_edicao == "" || $tipo_relatorio_edicao == "" || $data_relatorio_edicao == "") {
            $mensagem = "Preencha todos os campos.";
        } else {
            $sql_edicao = "UPDATE relatorio SET nome_relatorio = ?, tipo_relatorio = ?, data_relatorio = ? WHERE id_relatorio = ?";
            $stmt_edicao = $conexao->prepare($sql_edicao);
            $stmt_edicao->bind_param("sssi", $nome_relatorio_edicao, $tipo_relatorio_edicao, $data_relatorio_edicao, $id_relatorio_edicao);

            if ($stmt_edicao->execute()) {
                header("Location: visualizar-relatorio.php?sucesso=editado");
                exit;
            }

            $mensagem = "Erro ao editar o relatório.";
        }

        $tipo_mensagem = "danger";
    }
}

$sql = "SELECT * FROM relatorio ORDER BY id_relatorio ASC";
$resultado = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios cadastrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../../style/style.css?v=6">
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
                        <button type="button" class="btn btn-outline-light btn-sm d-flex align-items-center gap-2"
                            data-bs-toggle="modal" data-bs-target="#modalLogout">
                            <ion-icon name="log-out-outline"></ion-icon>
                            <span>Sair</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid p-4 crud-container">
        <div class="d-flex justify-content-between align-items-center mb-4 crud-cabecalho">
            <h1 class="h3">Lista de relatórios cadastrados</h1>
            <button type="button" class="btn btn-sistema d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#modalCadastroRelatorio">
                <ion-icon name="add-circle"></ion-icon>
                Novo relatório
            </button>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="coluna-nome">Nome do Relatório</th>
                                <th>Tipo</th>
                                <th>Data</th>
                                <th class="coluna-acoes">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado && $resultado->num_rows > 0) { ?>
                                <?php while ($relatorio = $resultado->fetch_assoc()) { ?>
                                    <tr>
                                        <td class="coluna-nome"><?= htmlspecialchars($relatorio["nome_relatorio"]) ?></td>
                                        <td><?= htmlspecialchars($relatorio["tipo_relatorio"]) ?></td>
                                        <td><?= htmlspecialchars($relatorio["data_relatorio"]) ?></td>
                                        <td class="coluna-acoes">
                                            <?php if (ehAdministrador()) { ?>
                                                <button type="button" class="btn btn-editar btn-sm" title="Editar" aria-label="Editar"
                                                    data-bs-toggle="modal" data-bs-target="#modalEditarRelatorio"
                                                    data-id="<?= $relatorio["id_relatorio"] ?>"
                                                    data-nome="<?= htmlspecialchars($relatorio["nome_relatorio"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-tipo="<?= htmlspecialchars($relatorio["tipo_relatorio"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-data="<?= htmlspecialchars($relatorio["data_relatorio"], ENT_QUOTES, 'UTF-8') ?>">
                                                    <ion-icon name="pencil" aria-hidden="true"></ion-icon>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" title="Excluir" aria-label="Excluir" onclick="abrirAviso(<?= $relatorio['id_relatorio'] ?>, 'excluir-relatorio.php')">
                                                    <ion-icon name="trash" aria-hidden="true"></ion-icon>
                                                </button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Nenhum relatório cadastrado.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php if ($mensagem != "" && !$abrir_modal && !$abrir_modal_edicao) { ?>
        <div class="alert alert-<?= $tipo_mensagem ?> alert-dismissible fade show m-4" role="alert">
            <?= htmlspecialchars($mensagem) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>
    <?php
    define("RELATORIO_MODAIS", true);
    require __DIR__ . "/cadastrar-relatorio.php";
    require __DIR__ . "/editar-relatorio.php";
    require __DIR__ . "/excluir-relatorio.php";
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script/crud.js"></script>

    <div class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="modalLogoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLogoutLabel">
                        <ion-icon name="log-out-outline"></ion-icon>
                        Confirmar logout
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Tem certeza que deseja sair do sistema?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-cancelar" data-bs-dismiss="modal">
                        <ion-icon name="close-outline"></ion-icon>
                        Cancelar
                    </button>
                    <a class="btn btn-danger btn-login px-4" href="../logout.php">
                        <ion-icon name="log-out-outline"></ion-icon>
                        Sair
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php if ($abrir_modal) { ?>
                new bootstrap.Modal(document.getElementById("modalCadastroRelatorio")).show();
            <?php } ?>
            <?php if ($abrir_modal_edicao) { ?>
                document.getElementById("editar_id_relatorio").value = <?= json_encode($id_relatorio_edicao) ?>;
                document.getElementById("editar_nome_relatorio").value = <?= json_encode($nome_relatorio_edicao) ?>;
                document.getElementById("editar_tipo_relatorio").value = <?= json_encode($tipo_relatorio_edicao) ?>;
                document.getElementById("editar_data_relatorio").value = <?= json_encode($data_relatorio_edicao) ?>;
                new bootstrap.Modal(document.getElementById("modalEditarRelatorio")).show();
            <?php } ?>
        });

        document.addEventListener("DOMContentLoaded", function () {
            let modalEdicaoRelatorio = document.getElementById("modalEditarRelatorio");
            if (modalEdicaoRelatorio) {
                modalEdicaoRelatorio.addEventListener("show.bs.modal", function (event) {
                    let botao = event.relatedTarget;
                    if (!botao) {
                        return;
                    }

                    document.getElementById("editar_id_relatorio").value = botao.dataset.id;
                    document.getElementById("editar_nome_relatorio").value = botao.dataset.nome;
                    document.getElementById("editar_tipo_relatorio").value = botao.dataset.tipo;
                    document.getElementById("editar_data_relatorio").value = botao.dataset.data;
                });
            }
        });
    </script>
</body>

</html>