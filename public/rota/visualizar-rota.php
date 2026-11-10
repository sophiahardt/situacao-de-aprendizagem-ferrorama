<?php
require_once "../../infra/protecao.php";
verificarLogin();
require_once "../../infra/conexao.php";

$total_colunas = ehAdministrador() ? 7 : 6;

function validarRota($nome_rota, $descricao, $extensao, $tempo_estimado)
{
    if ($nome_rota == "" || $descricao == "" || $extensao == "" || $tempo_estimado == "") {
        return "Preencha todos os campos.";
    }

    if (
        !is_numeric($extensao) ||
        (float) $extensao <= 0 ||
        !filter_var($tempo_estimado, FILTER_VALIDATE_INT) ||
        (int) $tempo_estimado <= 0
    ) {
        return "Informe uma extensão e um tempo estimado válidos.";
    }

    return "";
}

$mensagem = "";
$tipo_mensagem = "success";
$abrir_modal_edicao = false;

$abrir_modal = ($_GET["abrir"] ?? "") == "cadastro";

$nome_rota_form = "";
$descricao_form = "";
$extensao_form = "";
$tempo_estimado_form = "";
$id_trem_form = "";

$id_rota_edicao = "";
$nome_rota_edicao = "";
$descricao_edicao = "";
$extensao_edicao = "";
$tempo_estimado_edicao = "";
$id_trem_edicao = "";

$sucesso = $_GET["sucesso"] ?? "";
$erro = $_GET["erro"] ?? "";

if ($sucesso == "cadastrada") {
    $mensagem = "Rota cadastrada com sucesso!";
} elseif ($sucesso == "editada") {
    $mensagem = "Rota editada com sucesso!";
} elseif ($sucesso == "1") {
    $mensagem = "Rota excluída com sucesso!";
} elseif ($erro == "sensores") {
    $mensagem = "Não é possível excluir esta rota, pois existem sensores associados a ela.";
    $tipo_mensagem = "danger";
} elseif ($erro == "exclusao") {
    $mensagem = "Não foi possível excluir a rota.";
    $tipo_mensagem = "danger";
}

$consulta_trens = $conexao->query(
    "SELECT trem.id_trem, trem.nome_trem, rota.nome_rota
     FROM trem
     LEFT JOIN rota ON trem.id_rota = rota.id_rota
     ORDER BY trem.nome_trem"
);

$trens = $consulta_trens ? $consulta_trens->fetch_all(MYSQLI_ASSOC) : [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (($_POST["acao"] ?? "") == "cadastrar") {
        $nome_rota_form = trim($_POST["nome_rota"] ?? "");
        $descricao_form = trim($_POST["descricao"] ?? "");
        $extensao_form = $_POST["extensao"] ?? "";
        $tempo_estimado_form = $_POST["tempo_estimado_minutos"] ?? "";
        $id_trem_form = $_POST["id_trem"] ?? "";

        $abrir_modal = true;

        $mensagem = validarRota($nome_rota_form, $descricao_form, $extensao_form, $tempo_estimado_form);

        if ($mensagem == "" && $id_trem_form == "") {
            $mensagem = "Selecione o trem que anda nesta rota.";
        }

        if ($mensagem == "") {
            $extensao = (float) $extensao_form;
            $tempo_estimado_minutos = (int) $tempo_estimado_form;
            $id_trem = (int) $id_trem_form;

            $conexao->begin_transaction();

            try {
                $sql = "INSERT INTO rota (nome_rota, descricao, extensao, tempo_estimado_minutos)
                        VALUES (?, ?, ?, ?)";

                $stmt = $conexao->prepare($sql);

                $stmt->bind_param(
                    "ssdi",
                    $nome_rota_form,
                    $descricao_form,
                    $extensao,
                    $tempo_estimado_minutos
                );

                $stmt->execute();

                $id_rota_nova = $conexao->insert_id;

                $sql = "UPDATE trem SET id_rota = ? WHERE id_trem = ?";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("ii", $id_rota_nova, $id_trem);
                $stmt->execute();

                if ($stmt->affected_rows <= 0) {
                    throw new Exception("Trem não encontrado.");
                }

                $conexao->commit();

                header("Location: visualizar-rota.php?sucesso=cadastrada");
                exit;
            } catch (Throwable $excecao) {
                $conexao->rollback();
                $mensagem = "Erro ao cadastrar a rota.";
            }
        }

        $tipo_mensagem = "danger";
    } elseif (($_POST["acao"] ?? "") == "editar") {
        $id_rota_edicao = (int) ($_POST["id_rota"] ?? 0);
        $nome_rota_edicao = trim($_POST["nome_rota"] ?? "");
        $descricao_edicao = trim($_POST["descricao"] ?? "");
        $extensao_edicao = $_POST["extensao"] ?? "";
        $tempo_estimado_edicao = $_POST["tempo_estimado_minutos"] ?? "";
        $id_trem_edicao = $_POST["id_trem"] ?? "";

        $abrir_modal_edicao = true;

        if (!ehAdministrador()) {
            $mensagem = "Você não tem permissão para editar rotas.";
        } elseif ($id_rota_edicao <= 0) {
            $mensagem = "Preencha todos os campos.";
        } else {
            $mensagem = validarRota($nome_rota_edicao, $descricao_edicao, $extensao_edicao, $tempo_estimado_edicao);

            if ($mensagem == "" && $id_trem_edicao == "") {
                $mensagem = "Selecione o trem que anda nesta rota.";
            }
        }

        if ($mensagem == "") {
            $extensao = (float) $extensao_edicao;
            $tempo_estimado_minutos = (int) $tempo_estimado_edicao;
            $id_trem = (int) $id_trem_edicao;

            $conexao->begin_transaction();

            try {
                $sql = "UPDATE rota
                        SET nome_rota = ?,
                            descricao = ?,
                            extensao = ?,
                            tempo_estimado_minutos = ?
                        WHERE id_rota = ?";

                $stmt = $conexao->prepare($sql);

                $stmt->bind_param(
                    "ssdii",
                    $nome_rota_edicao,
                    $descricao_edicao,
                    $extensao,
                    $tempo_estimado_minutos,
                    $id_rota_edicao
                );

                $stmt->execute();

                // O trem que andava nesta rota e não foi o escolhido fica sem rota.
                $sql = "UPDATE trem SET id_rota = NULL WHERE id_rota = ? AND id_trem <> ?";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("ii", $id_rota_edicao, $id_trem);
                $stmt->execute();

                // O trem escolhido passa a andar nesta rota.
                $sql = "UPDATE trem SET id_rota = ? WHERE id_trem = ?";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("ii", $id_rota_edicao, $id_trem);
                $stmt->execute();

                // Confere se o trem escolhido existe 
                $sql = "SELECT id_trem FROM trem WHERE id_trem = ?";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("i", $id_trem);
                $stmt->execute();

                if ($stmt->get_result()->num_rows == 0) {
                    throw new Exception("Trem não encontrado.");
                }

                $conexao->commit();

                header("Location: visualizar-rota.php?sucesso=editada");
                exit;
            } catch (Throwable $excecao) {
                $conexao->rollback();
            }

            $mensagem = "Erro ao editar a rota.";
        }

        $tipo_mensagem = "danger";
    }
}

$sql = "SELECT rota.*,
               GROUP_CONCAT(trem.nome_trem ORDER BY trem.nome_trem SEPARATOR ', ') AS trens,
               MIN(trem.id_trem) AS id_trem
        FROM rota
        LEFT JOIN trem ON trem.id_rota = rota.id_rota
        GROUP BY rota.id_rota
        ORDER BY rota.id_rota";

$resultado = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Rotas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../../style/style.css?v=5">
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
                        <a class="nav-link active" href="../rota/visualizar-rota.php">Rotas</a>
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
                Lista de rotas cadastradas
            </h1>
            <button type="button" class="btn btn-sistema"
                data-bs-toggle="modal" data-bs-target="#modalCadastroRota">
                <ion-icon name="add-circle"></ion-icon>
                Nova rota
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Extensão</th>
                                <th>Tempo estimado</th>
                                <th>Trens</th>
                                <?php if (ehAdministrador()) { ?>
                                    <th>Ações</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado && $resultado->num_rows > 0) { ?>
                                <?php while ($rota = $resultado->fetch_assoc()) { ?>
                                    <tr>
                                        <td>
                                            <?= $rota["id_rota"] ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($rota["nome_rota"]) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($rota["descricao"]) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars((string) $rota["extensao"]) ?> Km
                                        </td>
                                        <td>
                                            <?= htmlspecialchars((string) $rota["tempo_estimado_minutos"]) ?> min
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($rota["trens"] ?? "Nenhum trem") ?>
                                        </td>
                                        <?php if (ehAdministrador()) { ?>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-editar btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditarRota"
                                                    data-id="<?= $rota["id_rota"] ?>"
                                                    data-nome="<?= htmlspecialchars($rota["nome_rota"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-descricao="<?= htmlspecialchars($rota["descricao"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-extensao="<?= htmlspecialchars((string) $rota["extensao"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-tempo="<?= htmlspecialchars((string) $rota["tempo_estimado_minutos"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-trem="<?= $rota["id_trem"] ?? "" ?>">
                                                    <ion-icon name="pencil"></ion-icon>
                                                    Editar
                                                </button>
                                                <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="abrirAviso(<?= $rota['id_rota'] ?>, 'excluir-rota.php')">
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
                                        Nenhuma rota cadastrada.
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
    define("ROTA_MODAIS", true);
    require __DIR__ . "/cadastrar-rota.php";
    require __DIR__ . "/editar-rota.php";
    require __DIR__ . "/excluir-rota.php";
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script/crud.js"></script>

    <!-- MODAIS DE CADASTRO E EDIÇÃO -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if ($abrir_modal) { ?>
                let modalCadastro = new bootstrap.Modal(
                    document.getElementById("modalCadastroRota")
                );
                modalCadastro.show();
            <?php } ?>

            <?php if ($abrir_modal_edicao) { ?>
                document.getElementById("editar_id_rota").value = <?= json_encode($id_rota_edicao) ?>;
                document.getElementById("editar_nome_rota").value = <?= json_encode($nome_rota_edicao) ?>;
                document.getElementById("editar_descricao").value = <?= json_encode($descricao_edicao) ?>;
                document.getElementById("editar_extensao").value = <?= json_encode($extensao_edicao) ?>;
                document.getElementById("editar_tempo_estimado_minutos").value = <?= json_encode($tempo_estimado_edicao) ?>;
                document.getElementById("editar_id_trem").value = <?= json_encode($id_trem_edicao) ?>;

                let modalEdicao = new bootstrap.Modal(
                    document.getElementById("modalEditarRota")
                );
                modalEdicao.show();
            <?php } ?>
        });
    </script>
</body>

</html>