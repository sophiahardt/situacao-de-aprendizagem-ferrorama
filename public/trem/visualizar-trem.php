<?php
require_once "../../infra/protecao.php";
verificarLogin();
require_once "../../infra/conexao.php";

$total_colunas = ehAdministrador() ? 6 : 5;

$tipos_trem = ["Passageiro", "Carga", "Expresso"];

function validarTrem($nome_trem, $velocidade_maxima, $tipo_trem, $id_usuario, $tipos_trem)
{
    if ($nome_trem == "" || $velocidade_maxima == "" || $tipo_trem == "" || $id_usuario == "") {
        return "Preencha todos os campos.";
    }

    if (!is_numeric($velocidade_maxima) || (float) $velocidade_maxima <= 0 || (float) $velocidade_maxima > 999.99) {
        return "Informe uma velocidade máxima válida (até 999,99).";
    }

    if (!in_array($tipo_trem, $tipos_trem, true)) {
        return "Selecione um tipo de trem válido.";
    }

    return "";
}

$mensagem = "";
$tipo_mensagem = "success";
$abrir_modal_edicao = false;

$abrir_modal = ($_GET["abrir"] ?? "") == "cadastro";

$nome_trem_form = "";
$velocidade_maxima_form = "";
$tipo_trem_form = "";
$id_usuario_form = "";

$id_trem_edicao = "";
$nome_trem_edicao = "";
$velocidade_maxima_edicao = "";
$tipo_trem_edicao = "";
$id_usuario_edicao = "";

$sucesso = $_GET["sucesso"] ?? "";
$erro = $_GET["erro"] ?? "";

if ($sucesso == "cadastrado") {
    $mensagem = "Trem cadastrado com sucesso!";
} elseif ($sucesso == "editado") {
    $mensagem = "Trem editado com sucesso!";
} elseif ($sucesso == "1") {
    $mensagem = "Trem excluído com sucesso!";
} elseif ($erro == "rotas") {
    $mensagem = "Não é possível excluir este trem, pois existem rotas associadas a ele.";
    $tipo_mensagem = "danger";
} elseif ($erro == "sensores") {
    $mensagem = "Não é possível excluir este trem, pois existem sensores associados a ele.";
    $tipo_mensagem = "danger";
} elseif ($erro == "exclusao") {
    $mensagem = "Não foi possível excluir o trem.";
    $tipo_mensagem = "danger";
}

$consulta_usuarios = $conexao->query("SELECT id_usuario, nome FROM usuario ORDER BY nome");

$usuarios = $consulta_usuarios ? $consulta_usuarios->fetch_all(MYSQLI_ASSOC) : [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (($_POST["acao"] ?? "") == "cadastrar") {
        $nome_trem_form = trim($_POST["nome_trem"] ?? "");
        $velocidade_maxima_form = $_POST["velocidade_maxima"] ?? "";
        $tipo_trem_form = $_POST["tipo_trem"] ?? "";
        $id_usuario_form = $_POST["id_usuario"] ?? "";

        $abrir_modal = true;

        $mensagem = validarTrem($nome_trem_form, $velocidade_maxima_form, $tipo_trem_form, $id_usuario_form, $tipos_trem);

        if ($mensagem == "") {
            $velocidade_maxima = (float) $velocidade_maxima_form;
            $id_usuario = (int) $id_usuario_form;

            $sql = "INSERT INTO trem
                    (nome_trem, velocidade_maxima, tipo_trem, id_usuario)
                    VALUES (?, ?, ?, ?)";

            $stmt = $conexao->prepare($sql);

            $stmt->bind_param(
                "sdsi",
                $nome_trem_form,
                $velocidade_maxima,
                $tipo_trem_form,
                $id_usuario
            );

            try {
                if ($stmt->execute()) {
                    header("Location: visualizar-trem.php?sucesso=cadastrado");
                    exit;
                }
            } catch (mysqli_sql_exception $excecao) {
            }

            $mensagem = "Erro ao cadastrar o trem.";
        }

        $tipo_mensagem = "danger";
    } elseif (($_POST["acao"] ?? "") == "editar") {
        $id_trem_edicao = (int) ($_POST["id_trem"] ?? 0);
        $nome_trem_edicao = trim($_POST["nome_trem"] ?? "");
        $velocidade_maxima_edicao = $_POST["velocidade_maxima"] ?? "";
        $tipo_trem_edicao = $_POST["tipo_trem"] ?? "";
        $id_usuario_edicao = $_POST["id_usuario"] ?? "";

        $abrir_modal_edicao = true;

        if (!ehAdministrador()) {
            $mensagem = "Você não tem permissão para editar trens.";
        } elseif ($id_trem_edicao <= 0) {
            $mensagem = "Preencha todos os campos.";
        } else {
            $mensagem = validarTrem($nome_trem_edicao, $velocidade_maxima_edicao, $tipo_trem_edicao, $id_usuario_edicao, $tipos_trem);
        }

        if ($mensagem == "") {
            $velocidade_maxima = (float) $velocidade_maxima_edicao;
            $id_usuario = (int) $id_usuario_edicao;

            $sql = "UPDATE trem
                    SET nome_trem = ?,
                        velocidade_maxima = ?,
                        tipo_trem = ?,
                        id_usuario = ?
                    WHERE id_trem = ?";

            $stmt = $conexao->prepare($sql);

            $stmt->bind_param(
                "sdsii",
                $nome_trem_edicao,
                $velocidade_maxima,
                $tipo_trem_edicao,
                $id_usuario,
                $id_trem_edicao
            );

            try {
                if ($stmt->execute()) {
                    header("Location: visualizar-trem.php?sucesso=editado");
                    exit;
                }
            } catch (mysqli_sql_exception $excecao) {
            }

            $mensagem = "Erro ao editar o trem.";
        }

        $tipo_mensagem = "danger";
    }
}

$sql = "SELECT trem.*,
               usuario.nome AS nome_responsavel
        FROM trem
        LEFT JOIN usuario ON trem.id_usuario = usuario.id_usuario";

$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Trens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../../style/style.css?v=6">
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
            <h1 class="h3">
                Lista de trens cadastrados
            </h1>
            <button type="button" class="btn btn-sistema"
                data-bs-toggle="modal" data-bs-target="#modalCadastroTrem">
                <ion-icon name="add-circle"></ion-icon>
                Novo trem
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="coluna-id">ID</th>
                                <th>Nome</th>
                                <th>Velocidade Máxima</th>
                                <th>Tipo</th>
                                <th>Responsável</th>
                                <?php if (ehAdministrador()) { ?>
                                    <th class="coluna-acoes">Ações</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado && $resultado->num_rows > 0) { ?>
                                <?php while ($trem = $resultado->fetch_assoc()) { ?>
                                    <tr>
                                        <td class="coluna-id">
                                            <?= $trem["id_trem"] ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($trem["nome_trem"]) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars((string) $trem["velocidade_maxima"]) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($trem["tipo_trem"]) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($trem["nome_responsavel"] ?? "Não definido") ?>
                                        </td>
                                        <?php if (ehAdministrador()) { ?>
                                            <td class="coluna-acoes">
                                                <button type="button"
                                                    class="btn btn-editar btn-sm" title="Editar" aria-label="Editar"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditarTrem"
                                                    data-id="<?= $trem["id_trem"] ?>"
                                                    data-nome="<?= htmlspecialchars($trem["nome_trem"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-velocidade="<?= htmlspecialchars((string) $trem["velocidade_maxima"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-tipo="<?= htmlspecialchars($trem["tipo_trem"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-usuario="<?= $trem["id_usuario"] ?? "" ?>">
                                                    <ion-icon name="pencil" aria-hidden="true"></ion-icon>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-danger btn-sm" title="Excluir" aria-label="Excluir"
                                                    onclick="abrirAviso(<?= $trem['id_trem'] ?>, 'excluir-trem.php')">
                                                    <ion-icon name="trash" aria-hidden="true"></ion-icon>
                                                </button>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="<?= $total_colunas ?>" class="text-center text-muted">
                                        Nenhum trem cadastrado.
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if ($mensagem != "" && !$abrir_modal && !$abrir_modal_edicao) { ?>
            <div class="alert alert-<?= $tipo_mensagem ?> alert-dismissible fade show mt-3" role="alert">
                <?= htmlspecialchars($mensagem) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Fechar"></button>
            </div>
        <?php } ?>
    </div>

    <?php
    define("TREM_MODAIS", true);
    require __DIR__ . "/cadastrar-trem.php";
    require __DIR__ . "/editar-trem.php";
    require __DIR__ . "/excluir-trem.php";
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

    <!-- MODAIS DE CADASTRO E EDIÇÃO -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if ($abrir_modal) { ?>
                let modalCadastro = new bootstrap.Modal(
                    document.getElementById("modalCadastroTrem")
                );
                modalCadastro.show();
            <?php } ?>

            <?php if ($abrir_modal_edicao) { ?>
                document.getElementById("editar_id_trem").value = <?= json_encode($id_trem_edicao) ?>;
                document.getElementById("editar_nome_trem").value = <?= json_encode($nome_trem_edicao) ?>;
                document.getElementById("editar_velocidade_maxima").value = <?= json_encode($velocidade_maxima_edicao) ?>;
                document.getElementById("editar_tipo_trem").value = <?= json_encode($tipo_trem_edicao) ?>;
                document.getElementById("editar_id_usuario").value = <?= json_encode($id_usuario_edicao) ?>;

                let modalEdicao = new bootstrap.Modal(
                    document.getElementById("modalEditarTrem")
                );
                modalEdicao.show();
            <?php } ?>
        });
    </script>
</body>

</html>