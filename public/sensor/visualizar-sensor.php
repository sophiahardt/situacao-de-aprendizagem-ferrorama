
<?php
// Inclui o arquivo responsável pela proteção das páginas.
require_once "../../infra/protecao.php";

// Verifica se o usuário está logado.
verificarLogin();

// Inclui o arquivo de conexão com o banco de dados.
require_once "../../infra/conexao.php";

// Define a quantidade de colunas da tabela conforme o tipo de usuário.
// O administrador visualiza também a coluna de ações.
$total_colunas = ehAdministrador() ? 5 : 4;

// Função responsável por validar os dados informados no cadastro ou edição.
function validarSensor($nome_sensor, $tipo_sensor, $localizacao, $id_localizacao)
{
    // Verifica se todos os campos foram preenchidos.
    if ($nome_sensor == "" || $tipo_sensor == "" || $localizacao == "" || $id_localizacao == "") {
        return "Preencha todos os campos.";
    }

    // Verifica se a localização selecionada é uma rota ou um trem.
    if ($localizacao != "rota" && $localizacao != "trem") {
        return "Selecione uma localização válida.";
    }

    // Retorna vazio quando os dados estão corretos.
    return "";
}

// Função que separa o ID da rota ou do trem conforme a localização escolhida.
function separarLocalizacao($localizacao, $id_localizacao)
{
    // Se a localização for uma rota, o ID do trem fica nulo.
    if ($localizacao == "rota") {
        return [null, (int) $id_localizacao];
    }

    // Se for um trem, o ID da rota fica nulo.
    return [(int) $id_localizacao, null];
}

// Inicializa as variáveis utilizadas nas mensagens e nos modais.
$mensagem = "";
$tipo_mensagem = "success";
$abrir_modal = false;
$abrir_modal_edicao = false;

// Inicializa os campos utilizados no formulário de cadastro.
$nome_sensor_form = "";
$tipo_sensor_form = "";
$localizacao_form = "";
$id_localizacao_form = "";

// Inicializa os campos utilizados no formulário de edição.
$nome_sensor_edicao = "";
$tipo_sensor_edicao = "";
$localizacao_edicao = "";
$id_localizacao_edicao = "";
$id_sensor_edicao = "";

// Recupera os parâmetros enviados pela URL.
$sucesso = $_GET["sucesso"] ?? "";
$erro = $_GET["erro"] ?? "";

// Verifica se alguma operação foi realizada com sucesso ou apresentou erro.
if ($sucesso == "cadastrado") {
    $mensagem = "Sensor cadastrado com sucesso!";
} elseif ($sucesso == "editado") {
    $mensagem = "Sensor editado com sucesso!";
} elseif ($sucesso == "1") {
    $mensagem = "Sensor excluído com sucesso!";
} elseif ($erro == "leituras") {
    $mensagem = "Não é possível excluir este sensor, pois existem leituras associadas.";
    $tipo_mensagem = "danger";
} elseif ($erro == "exclusao") {
    $mensagem = "Não foi possível excluir o sensor.";
    $tipo_mensagem = "danger";
}

// Busca as rotas e os trens cadastrados no banco de dados.
$consulta_rotas = $conexao->query("SELECT id_rota, nome_rota FROM rota ORDER BY nome_rota");
$consulta_trens = $conexao->query("SELECT id_trem, nome_trem FROM trem ORDER BY nome_trem");

// Armazena os resultados das consultas em arrays.
$rotas = $consulta_rotas ? $consulta_rotas->fetch_all(MYSQLI_ASSOC) : [];
$trens = $consulta_trens ? $consulta_trens->fetch_all(MYSQLI_ASSOC) : [];

// Verifica se o formulário foi enviado pelo método POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verifica se a ação solicitada foi cadastrar um sensor.
    if (($_POST["acao"] ?? "") == "cadastrar") {

        // Recebe os dados enviados pelo formulário.
        $nome_sensor_form = trim($_POST["nome_sensor"] ?? "");
        $tipo_sensor_form = $_POST["tipo_sensor"] ?? "";
        $localizacao_form = $_POST["localizacao"] ?? "";
        $id_localizacao_form = $_POST["id_localizacao"] ?? "";

        // Define que o modal de cadastro deverá ser aberto novamente.
        $abrir_modal = true;

        // Valida os dados informados no formulário.
        $mensagem = validarSensor($nome_sensor_form, $tipo_sensor_form, $localizacao_form, $id_localizacao_form);

        // Continua o cadastro somente se não houver erros de validação.
        if ($mensagem == "") {

            // Separa o ID da rota ou do trem para armazenar corretamente no banco.
            [$id_trem, $id_rota] = separarLocalizacao($localizacao_form, $id_localizacao_form);

            // Comando SQL para inserir um novo sensor.
            // Os pontos de interrogação representam os valores que serão enviados.
            $sql = "INSERT INTO sensor
                    (nome_sensor, tipo_sensor, localizacao, id_trem, id_rota)
                    VALUES (?, ?, ?, ?, ?)";

            // Prepara a consulta para receber os valores.
            $stmt = $conexao->prepare($sql);

            // Associa os valores aos campos da consulta SQL.
            $stmt->bind_param(
                "sssii",
                $nome_sensor_form,
                $tipo_sensor_form,
                $localizacao_form,
                $id_trem,
                $id_rota
            );

            // Tenta executar o cadastro no banco de dados.
            try {
                if ($stmt->execute()) {

                    // Redireciona para a página com a mensagem de sucesso.
                    header("Location: visualizar-sensor.php?sucesso=cadastrado");
                    exit;
                }
            } catch (mysqli_sql_exception $excecao) {
            }

            // Exibe uma mensagem caso o cadastro não seja realizado.
            $mensagem = "Erro ao cadastrar o sensor.";
        }

        // Define o tipo de mensagem como erro.
        $tipo_mensagem = "danger";

    // Verifica se a ação solicitada foi editar um sensor.
    } elseif (($_POST["acao"] ?? "") == "editar") {

        // Recebe os dados enviados pelo formulário de edição.
        $id_sensor_edicao = (int) ($_POST["id_sensor"] ?? 0);
        $nome_sensor_edicao = trim($_POST["nome_sensor"] ?? "");
        $tipo_sensor_edicao = $_POST["tipo_sensor"] ?? "";
        $localizacao_edicao = $_POST["localizacao"] ?? "";
        $id_localizacao_edicao = $_POST["id_localizacao"] ?? "";

        // Define que o modal de edição deverá ser aberto novamente.
        $abrir_modal_edicao = true;

        // Verifica se o usuário possui permissão de administrador.
        if (!ehAdministrador()) {
            $mensagem = "Você não tem permissão para editar sensores.";

        // Verifica se o ID do sensor é válido.
        } elseif ($id_sensor_edicao <= 0) {
            $mensagem = "Preencha todos os campos.";

        // Valida os dados informados para a edição.
        } else {
            $mensagem = validarSensor($nome_sensor_edicao, $tipo_sensor_edicao, $localizacao_edicao, $id_localizacao_edicao);
        }

        // Continua a edição somente se não houver erros.
        if ($mensagem == "") {

            // Separa o ID da rota ou do trem conforme a localização escolhida.
            [$id_trem, $id_rota] = separarLocalizacao($localizacao_edicao, $id_localizacao_edicao);

            // Comando SQL para atualizar os dados do sensor.
            $sql = "UPDATE sensor
                    SET nome_sensor = ?,
                        tipo_sensor = ?,
                        localizacao = ?,
                        id_trem = ?,
                        id_rota = ?
                    WHERE id_sensor = ?";

            // Prepara a consulta para receber os novos valores.
            $stmt = $conexao->prepare($sql);

            // Associa os valores aos campos da consulta SQL.
            $stmt->bind_param(
                "sssiii",
                $nome_sensor_edicao,
                $tipo_sensor_edicao,
                $localizacao_edicao,
                $id_trem,
                $id_rota,
                $id_sensor_edicao
            );

            // Tenta executar a atualização no banco de dados.
            try {
                if ($stmt->execute()) {

                    // Redireciona para a página com a mensagem de sucesso.
                    header("Location: visualizar-sensor.php?sucesso=editado");
                    exit;
                }
            } catch (mysqli_sql_exception $excecao) {
            }

            // Exibe uma mensagem caso a edição não seja realizada.
            $mensagem = "Erro ao editar o sensor.";
        }

        // Define o tipo de mensagem como erro.
        $tipo_mensagem = "danger";
    }
}

// Consulta os sensores cadastrados e suas respectivas rotas e trens.
// O LEFT JOIN permite relacionar os dados mesmo quando uma das localizações está vazia.
$sql = "SELECT sensor.*,
               rota.nome_rota,
               trem.nome_trem
        FROM sensor
        LEFT JOIN rota ON sensor.id_rota = rota.id_rota
        LEFT JOIN trem ON sensor.id_trem = trem.id_trem";

// Executa a consulta e armazena o resultado.
$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <!-- Define a codificação e a configuração de visualização da página. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Sensores</title>

    <!-- Importa o Bootstrap para utilizar seus componentes e estilos. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Importa a biblioteca de ícones Ionicons. -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <!-- Importa o arquivo CSS principal do sistema. -->
    <link rel="stylesheet" href="../../style/style.css?v=6">
</head>

<body>

    <!-- Barra de navegação principal do sistema. -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-sistema">
        <div class="container-fluid">

            <!-- Botão utilizado para abrir o menu em telas menores. -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <!-- Links de navegação do sistema. -->
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

                    <!-- Exibe o link de usuários somente para administradores. -->
                    <?php if (ehAdministrador()) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../user/visualizar-user.php">Usuários</a>
                        </li>
                    <?php } ?>

                    <li class="nav-item">
                        <a class="nav-link" href="../relatorio/relatorio-geral.php">Relatórios</a>
                    </li>
                </ul>

                <!-- Exibe o nome do usuário conectado e a opção de sair. -->
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

    <!-- Área principal da página de visualização dos sensores. -->
    <div class="container-fluid p-4 crud-container">

        <!-- Cabeçalho com o título e o botão de cadastro. -->
        <div class="d-flex justify-content-between align-items-center mb-4 crud-cabecalho">
            <h1 class="h3">
                Lista de sensores cadastrados
            </h1>

            <!-- Botão que abre o modal de cadastro de sensores. -->
            <button type="button" class="btn btn-sistema"
                data-bs-toggle="modal" data-bs-target="#modalCadastroSensor">
                <ion-icon name="add-circle"></ion-icon>
                Novo sensor
            </button>
        </div>

        <!-- Card que contém a tabela de sensores. -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="coluna-id">ID</th>
                                <th>Nome</th>
                                <th>Localização</th>
                                <th>Tipo de dado</th>

                                <!-- A coluna de ações aparece somente para administradores. -->
                                <?php if (ehAdministrador()) { ?>
                                    <th class="coluna-acoes">Ações</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- Verifica se existem sensores cadastrados no banco de dados. -->
                            <?php if ($resultado && $resultado->num_rows > 0) { ?>

                                <!-- Percorre os sensores encontrados na consulta. -->
                                <?php while ($sensor = $resultado->fetch_assoc()) { ?>
                                    <tr>
                                        <td class="coluna-id">
                                            <?= $sensor["id_sensor"] ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($sensor["nome_sensor"]) ?>
                                        </td>
                                        <td>
                                            <?php
                                            // Verifica se a localização do sensor é uma rota ou um trem.
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

                                        <!-- Exibe os botões de edição e exclusão somente para administradores. -->
                                        <?php if (ehAdministrador()) { ?>
                                            <td class="coluna-acoes">

                                                <!-- Botão que abre o modal de edição e envia os dados do sensor. -->
                                                <button type="button"
                                                    class="btn btn-editar btn-sm" title="Editar" aria-label="Editar"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditarSensor"
                                                    data-id="<?= $sensor["id_sensor"] ?>"
                                                    data-nome="<?= htmlspecialchars($sensor["nome_sensor"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-tipo="<?= htmlspecialchars($sensor["tipo_sensor"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-localizacao="<?= htmlspecialchars($sensor["localizacao"], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-rota="<?= $sensor["id_rota"] ?? "" ?>"
                                                    data-trem="<?= $sensor["id_trem"] ?? "" ?>">
                                                    <ion-icon name="pencil" aria-hidden="true"></ion-icon>
                                                </button>

                                                <!-- Botão que chama a confirmação antes de excluir o sensor. -->
                                                <button type="button"
                                                    class="btn btn-danger btn-sm" title="Excluir" aria-label="Excluir"
                                                    onclick="abrirAviso(<?= $sensor['id_sensor'] ?>, 'excluir-sensor.php')">
                                                    <ion-icon name="trash" aria-hidden="true"></ion-icon>
                                                </button>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>

                            <!-- Exibe uma mensagem caso não existam sensores cadastrados. -->
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

        <!-- Exibe mensagens de sucesso ou erro quando nenhum modal precisa ser aberto. -->
        <?php if ($mensagem != "" && !$abrir_modal && !$abrir_modal_edicao) { ?>
            <div class="alert alert-<?= $tipo_mensagem ?> alert-dismissible fade show mt-3" role="alert">
                <?= htmlspecialchars($mensagem) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Fechar"></button>
            </div>
        <?php } ?>
    </div>

    <?php

    // Define que os arquivos de modais serão utilizados nesta página.
    define("SENSOR_MODAIS", true);

    // Inclui os arquivos responsáveis pelos modais de cadastro, edição e exclusão.
    require __DIR__ . "/cadastrar-sensor.php";
    require __DIR__ . "/editar-sensor.php";
    require __DIR__ . "/excluir-sensor.php";
    ?>

    <!-- Importa o JavaScript do Bootstrap e o arquivo de funções dos CRUDs. -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script/crud.js"></script>

    <!-- Modal utilizado para confirmar a saída do sistema. -->
    <div class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="modalLogoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLogoutLabel">
                        <ion-icon name="log-out-outline"></ion-icon>
                        Confirmar logout
                    </h5>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Tem certeza que deseja sair do sistema?</p>
                </div>
                <div class="modal-footer">

                    <!-- Botão que fecha o modal sem sair do sistema. -->
                    <button type="button" class="btn btn-light btn-cancelar" data-bs-dismiss="modal">
                        <ion-icon name="close-outline"></ion-icon>
                        Cancelar
                    </button>

                    <!-- Link que direciona o usuário para a página de logout. -->
                    <a class="btn btn-danger btn-login px-4" href="../logout.php">
                        <ion-icon name="log-out-outline"></ion-icon>
                        Sair
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Script responsável por abrir novamente os modais após uma validação. -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Verifica se o formulário de cadastro precisa ser aberto novamente.
            <?php if ($abrir_modal) { ?>
                let modalCadastro = new bootstrap.Modal(
                    document.getElementById("modalCadastroSensor")
                );
                modalCadastro.show();
            <?php } ?>

            // Verifica se o formulário de edição precisa ser aberto novamente.
            <?php if ($abrir_modal_edicao) { ?>

                // Preenche os campos do formulário com os dados enviados anteriormente.
                document.getElementById("editar_id_sensor").value = <?= json_encode($id_sensor_edicao) ?>;
                document.getElementById("editar_nome_sensor").value = <?= json_encode($nome_sensor_edicao) ?>;
                document.getElementById("editar_tipo_sensor").value = <?= json_encode($tipo_sensor_edicao) ?>;

                // Verifica se a localização selecionada é uma rota.
                if (<?= json_encode($localizacao_edicao) ?> == "rota") {
                    document.getElementById("editar_rota").checked = true;

                    // Atualiza os campos de localização do formulário.
                    verificarLocalizacaoEdicao();

                    // Preenche o ID da rota selecionada.
                    document.getElementById("editar_id_rota").value = <?= json_encode($id_localizacao_edicao) ?>;

                // Verifica se a localização selecionada é um trem.
                } else if (<?= json_encode($localizacao_edicao) ?> == "trem") {
                    document.getElementById("editar_trem").checked = true;

                    // Atualiza os campos de localização do formulário.
                    verificarLocalizacaoEdicao();

                    // Preenche o ID do trem selecionado.
                    document.getElementById("editar_id_trem").value = <?= json_encode($id_localizacao_edicao) ?>;
                }

                // Abre novamente o modal de edição.
                let modalEdicao = new bootstrap.Modal(
                    document.getElementById("modalEditarSensor")
                );
                modalEdicao.show();
            <?php } ?>
        });
    </script>
</body>

</html>
