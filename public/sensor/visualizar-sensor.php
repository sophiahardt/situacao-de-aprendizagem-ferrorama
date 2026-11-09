<?php
require_once "../../infra/protecao.php";
verificarLogin();
require_once "../../infra/conexao.php";

$total_colunas = ehAdministrador() ? 5 : 4;

$mensagem = "";
$tipo_mensagem = "success";
$abrir_modal = false;
$abrir_modal_edicao = false;

$nome_sensor_form = "";
$tipo_sensor_form = "";
$localizacao_form = "";
$id_localizacao_form = "";

$nome_sensor_edicao = "";
$tipo_sensor_edicao = "";
$localizacao_edicao = "";
$id_localizacao_edicao = "";
$id_sensor_edicao = "";

if (isset($_GET["sucesso"]) && $_GET["sucesso"] == "cadastrado") {
    $mensagem = "Sensor cadastrado com sucesso!";
} elseif (isset($_GET["sucesso"]) && $_GET["sucesso"] == "editado") {
    $mensagem = "Sensor editado com sucesso!";
} elseif (isset($_GET["sucesso"]) && $_GET["sucesso"] == "1") {
    $mensagem = "Sensor excluído com sucesso!";
} elseif (isset($_GET["erro"]) && $_GET["erro"] == "leituras") {
    $mensagem = "Não é possível excluir este sensor, pois existem leituras associadas.";
    $tipo_mensagem = "danger";
} elseif (isset($_GET["erro"]) && $_GET["erro"] == "exclusao") {
    $mensagem = "Não foi possível excluir o sensor.";
    $tipo_mensagem = "danger";
}

$consulta_rotas = $conexao->query("SELECT id_rota, nome_rota FROM rota ORDER BY nome_rota");
$consulta_trens = $conexao->query("SELECT id_trem, nome_trem FROM trem ORDER BY nome_trem");

$rotas = [];
$trens = [];

if ($consulta_rotas) {
    while ($rota = $consulta_rotas->fetch_assoc()) {
        $rotas[] = $rota;
    }
}

if ($consulta_trens) {
    while ($trem = $consulta_trens->fetch_assoc()) {
        $trens[] = $trem;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mensagem = "";
    $tipo_mensagem = "success";
    if (($_POST["acao"] ?? "") == "cadastrar") {
        $nome_sensor_form = trim($_POST["nome_sensor"] ?? "");
        $tipo_sensor_form = $_POST["tipo_sensor"] ?? "";
        $localizacao_form = $_POST["localizacao"] ?? "";
        $id_localizacao_form = $_POST["id_localizacao"] ?? "";

        $abrir_modal = true;

        if (!ehAdministrador()) {
            $mensagem = "Você não tem permissão para cadastrar sensores.";
            $tipo_mensagem = "danger";
        } elseif ($nome_sensor_form == "" || $tipo_sensor_form == "" || $localizacao_form == "" || $id_localizacao_form == "") {
            $mensagem = "Preencha todos os campos.";
            $tipo_mensagem = "danger";
        } else {
            $id_rota = null;
            $id_trem = null;

            if ($localizacao_form == "rota") {
                $id_rota = (int) $id_localizacao_form;
            } elseif ($localizacao_form == "trem") {
                $id_trem = (int) $id_localizacao_form;
            } else {
                $mensagem = "Selecione uma localização válida.";
                $tipo_mensagem = "danger";
            }

            if ($mensagem == "") {
                $sql = "INSERT INTO sensor
                        (nome_sensor, tipo_sensor, localizacao, id_trem, id_rota)
                        VALUES (?, ?, ?, ?, ?)";

                $stmt = $conexao->prepare($sql);

                $stmt->bind_param(
                    "sssii",
                    $nome_sensor_form,
                    $tipo_sensor_form,
                    $localizacao_form,
                    $id_trem,
                    $id_rota
                );

                if ($stmt->execute()) {
                    header("Location: visualizar-sensor.php?sucesso=cadastrado");
                    exit;
                } else {
                    $mensagem = "Erro ao cadastrar o sensor.";
                    $tipo_mensagem = "danger";
                }
            }
        }
    } elseif (($_POST["acao"] ?? "") == "editar") {
        $id_sensor_edicao = (int) ($_POST["id_sensor"] ?? 0);
        $nome_sensor_edicao = trim($_POST["nome_sensor"] ?? "");
        $tipo_sensor_edicao = $_POST["tipo_sensor"] ?? "";
        $localizacao_edicao = $_POST["localizacao"] ?? "";
        $id_localizacao_edicao = $_POST["id_localizacao"] ?? "";

        $abrir_modal_edicao = true;

        if (!ehAdministrador()) {
            $mensagem = "Você não tem permissão para editar sensores.";
            $tipo_mensagem = "danger";
        } elseif ($id_sensor_edicao <= 0 || $nome_sensor_edicao == "" || $tipo_sensor_edicao == "" || $localizacao_edicao == "" || $id_localizacao_edicao == "") {
            $mensagem = "Preencha todos os campos.";
            $tipo_mensagem = "danger";
        } elseif ($localizacao_edicao != "rota" && $localizacao_edicao != "trem") {
            $mensagem = "Selecione uma localização válida.";
            $tipo_mensagem = "danger";
        } else {
            $id_rota = null;
            $id_trem = null;

            if ($localizacao_edicao == "rota") {
                $id_rota = (int) $id_localizacao_edicao;
            } else {
                $id_trem = (int) $id_localizacao_edicao;
            }

            $sql = "UPDATE sensor
                    SET nome_sensor = ?,
                        tipo_sensor = ?,
                        localizacao = ?,
                        id_trem = ?,
                        id_rota = ?
                    WHERE id_sensor = ?";

            $stmt = $conexao->prepare($sql);

            $stmt->bind_param(
                "sssiii",
                $nome_sensor_edicao,
                $tipo_sensor_edicao,
                $localizacao_edicao,
                $id_trem,
                $id_rota,
                $id_sensor_edicao
            );

            if ($stmt->execute()) {
                header("Location: visualizar-sensor.php?sucesso=editado");
                exit;
            } else {
                $mensagem = "Erro ao editar o sensor.";
                $tipo_mensagem = "danger";
            }
        }
    }
}

$sql = "SELECT sensor.*,
               rota.nome_rota,
               trem.nome_trem
        FROM sensor
        LEFT JOIN rota ON sensor.id_rota = rota.id_rota
        LEFT JOIN trem ON sensor.id_trem = trem.id_trem";

$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Sensores</title>
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
                Lista de sensores cadastrados
            </h1>
            <?php if (ehAdministrador()) { ?>
                <button type="button" class="btn btn-primary"
                    style="background-color: #151d4b;"
                    data-bs-toggle="modal" data-bs-target="#modalCadastroSensor">
                    <ion-icon name="add-circle"></ion-icon>
                    Novo sensor
                </button>
            <?php } ?>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Localização</th>
                                <th>Tipo de dado</th>
                                <?php if (ehAdministrador()) { ?>
                                    <th>Ações</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado && $resultado->num_rows > 0) { ?>
                                <?php while ($sensor = $resultado->fetch_assoc()) { ?>
                                    <tr>
                                        <td>
                                            <?= $sensor["id_sensor"] ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($sensor["nome_sensor"]) ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($sensor["localizacao"] == "rota") {
                                                echo "Rotas: " . htmlspecialchars($sensor["nome_rota"] ?? "Não identificada");
                                            } else {
                                                echo "Trens: " . htmlspecialchars($sensor["nome_trem"] ?? "Não identificado");
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($sensor["tipo_sensor"]) ?>
                                        </td>
                                        <?php if (ehAdministrador()) { ?>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-primary btn-sm"
                                                    style="background-color: #071b8d;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditarSensor"
                                                    data-id="<?= $sensor["id_sensor"] ?>"
                                                    data-nome="<?= htmlspecialchars($sensor["nome_sensor"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-tipo="<?= htmlspecialchars($sensor["tipo_sensor"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-localizacao="<?= htmlspecialchars($sensor["localizacao"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-rota="<?= $sensor["id_rota"] ?? "" ?>"
                                                    data-trem="<?= $sensor["id_trem"] ?? "" ?>">
                                                    <ion-icon name="pencil"></ion-icon>
                                                    Editar
                                                </button>
                                                <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="abrirAviso(<?= $sensor['id_sensor'] ?>, 'excluir-sensor.php')">
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
                                        Nenhum sensor cadastrado.
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
    
    define("SENSOR_MODAIS", true);
    require __DIR__ . "/cadastrar-sensor.php";
    require __DIR__ . "/editar-sensor.php";
    require __DIR__ . "/excluir-sensor.php";
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script/botoes.js"></script>
    <script src="../../script/sensor.js"></script>

    <!-- MODAIS DE CADASTRO E EDIÇÃO -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if ($abrir_modal) { ?>
                let modalCadastro = new bootstrap.Modal(
                    document.getElementById("modalCadastroSensor")
                );
                modalCadastro.show();
            <?php } ?>

            <?php if ($abrir_modal_edicao) { ?>
                document.getElementById("editar_id_sensor").value = <?= json_encode($id_sensor_edicao) ?>;
                document.getElementById("editar_nome_sensor").value = <?= json_encode($nome_sensor_edicao) ?>;
                document.getElementById("editar_tipo_sensor").value = <?= json_encode($tipo_sensor_edicao) ?>;

                if (<?= json_encode($localizacao_edicao) ?> == "rota") {
                    document.getElementById("editar_rota").checked = true;
                    verificarLocalizacaoEdicao();
                    document.getElementById("editar_id_rota").value = <?= json_encode($id_localizacao_edicao) ?>;
                } else if (<?= json_encode($localizacao_edicao) ?> == "trem") {
                    document.getElementById("editar_trem").checked = true;
                    verificarLocalizacaoEdicao();
                    document.getElementById("editar_id_trem").value = <?= json_encode($id_localizacao_edicao) ?>;
                }

                let modalEdicao = new bootstrap.Modal(
                    document.getElementById("modalEditarSensor")
                );
                modalEdicao.show();
            <?php } ?>
        });
    </script>
</body>

</html>