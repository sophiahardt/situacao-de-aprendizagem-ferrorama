<?php
require_once "../../infra/protecao.php";
verificarLogin();
require_once "../../infra/conexao.php";

// Define a quantidade de colunas da tabela de acordo com o tipo de usuário
// Administradores visualizam 6 colunas, enquanto funcionários visualizam 5
$total_colunas = ehAdministrador() ? 6 : 5;

// Array que armazena os tipos de trem permitidos no sistema
$tipos_trem = ["Passageiro", "Carga", "Expresso"];

// Função responsável por validar os dados informados no cadastro e na edição de trens
function validarTrem($nome_trem, $velocidade_maxima, $tipo_trem, $id_usuario, $tipos_trem)
{
    // Verifica se algum dos campos obrigatórios está vazio
    if ($nome_trem == "" || $velocidade_maxima == "" || $tipo_trem == "" || $id_usuario == "") {
        return "Preencha todos os campos.";
    }

    // Verifica se a velocidade é numérica, maior que zero e não ultrapassa 999,99
    if (!is_numeric($velocidade_maxima) || (float) $velocidade_maxima <= 0 || (float) $velocidade_maxima > 999.99) {
        return "Informe uma velocidade máxima válida (até 999,99).";
    }

    // Verifica se o tipo de trem informado existe no array de tipos permitidos
    // O parâmetro true faz com que a comparação também considere o tipo do valor
    if (!in_array($tipo_trem, $tipos_trem, true)) {
        return "Selecione um tipo de trem válido.";
    }

    // Retorna uma string vazia quando todas as validações são aprovadas
    return "";
}

// Cria a variável que armazena as mensagens exibidas ao usuário
$mensagem = "";
// Define o tipo de alerta como sucesso inicialmente
$tipo_mensagem = "success";
// Controla se o modal de edição deve ser aberto novamente
$abrir_modal_edicao = false;

// Verifica se o parâmetro "abrir" recebido pela URL possui o valor "cadastro"
// O operador ?? define uma string vazia caso o parâmetro não exista
$abrir_modal = ($_GET["abrir"] ?? "") == "cadastro";

// Cria as variáveis que armazenam os dados preenchidos no formulário de cadastro
$nome_trem_form = "";
$velocidade_maxima_form = "";
$tipo_trem_form = "";
$id_usuario_form = "";

// Cria as variáveis que armazenam os dados preenchidos no formulário de edição
$id_trem_edicao = "";
$nome_trem_edicao = "";
$velocidade_maxima_edicao = "";
$tipo_trem_edicao = "";
$id_usuario_edicao = "";

// Recupera os parâmetros de sucesso e erro enviados pela URL
// Caso não existam, atribui uma string vazia
$sucesso = $_GET["sucesso"] ?? "";
$erro = $_GET["erro"] ?? "";

// Verifica qual parâmetro foi recebido pela URL para definir a mensagem exibida
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

// Executa uma consulta para buscar os IDs e nomes dos usuários cadastrados
// Os resultados são organizados em ordem alfabética pelo nome
$consulta_usuarios = $conexao->query("SELECT id_usuario, nome FROM usuario ORDER BY nome");

// Verifica se a consulta foi executada corretamente
// Se houver resultado, utiliza fetch_all() para armazenar todos os registros em um array
// Caso contrário, inicializa o array vazio
$usuarios = $consulta_usuarios ? $consulta_usuarios->fetch_all(MYSQLI_ASSOC) : [];

// Verifica se a página recebeu uma requisição pelo método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se a ação recebida pelo formulário é cadastrar
    if (($_POST["acao"] ?? "") == "cadastrar") {
        // Recupera os valores enviados pelo formulário de cadastro (em caso de erro, os valores são mantidos)
        $nome_trem_form = trim($_POST["nome_trem"] ?? "");
        $velocidade_maxima_form = $_POST["velocidade_maxima"] ?? "";
        $tipo_trem_form = $_POST["tipo_trem"] ?? "";
        $id_usuario_form = $_POST["id_usuario"] ?? "";

        // Define que o modal de cadastro deve permanecer aberto após o envio
        $abrir_modal = true;

        // Chama a função validarTrem() para verificar os dados informados
        // A mensagem retornada será vazia se todos os dados forem válidos
        $mensagem = validarTrem($nome_trem_form, $velocidade_maxima_form, $tipo_trem_form, $id_usuario_form, $tipos_trem);

        // Verifica se não foram encontrados erros na validação
        if ($mensagem == "") {
            // Converte a velocidade para o tipo decimal (float)
            $velocidade_maxima = (float) $velocidade_maxima_form;
            // Converte o ID do usuário para o tipo inteiro (int)
            $id_usuario = (int) $id_usuario_form;

            // Comando SQL responsável por inserir um novo trem na tabela
            $sql = "INSERT INTO trem
                    (nome_trem, velocidade_maxima, tipo_trem, id_usuario)
                    VALUES (?, ?, ?, ?)";

            // Prepara a consulta SQL para receber os valores
            $stmt = $conexao->prepare($sql);

            // Associa os valores aos parâmetros da consulta
            // s = string, d = decimal, i = inteiro
            $stmt->bind_param(
                "sdsi",
                $nome_trem_form,
                $velocidade_maxima,
                $tipo_trem_form,
                $id_usuario
            );

            // Tenta executar a consulta e cadastrar o trem
            try {
                if ($stmt->execute()) {
                    // Redireciona para a página principal com o parâmetro de sucesso (exibindo mensagem de sucesso)
                    header("Location: visualizar-trem.php?sucesso=cadastrado");
                    exit;
                }
            } catch (mysqli_sql_exception $excecao) {
                // Captura possíveis erros/exceções do MySQL durante a execução da consulta
            }
            // Caso o cadastro não seja realizado, define uma mensagem de erro
            $mensagem = "Erro ao cadastrar o trem.";
        }

        $tipo_mensagem = "danger";
        // Verifica se a ação recebida pelo formulário é editar
    } elseif (($_POST["acao"] ?? "") == "editar") {
        // Recupera os dados enviados pelo formulário de edição
        $id_trem_edicao = (int) ($_POST["id_trem"] ?? 0);
        $nome_trem_edicao = trim($_POST["nome_trem"] ?? "");
        $velocidade_maxima_edicao = $_POST["velocidade_maxima"] ?? "";
        $tipo_trem_edicao = $_POST["tipo_trem"] ?? "";
        $id_usuario_edicao = $_POST["id_usuario"] ?? "";

        // Define que o modal de edição deve permanecer aberto após o envio
        $abrir_modal_edicao = true;

        // Verifica se o usuário possui permissão de administrador
        if (!ehAdministrador()) {
            $mensagem = "Você não tem permissão para editar trens.";
        // Verifica se o ID do trem é inválido ou menor ou igual a zero
        } elseif ($id_trem_edicao <= 0) {
            $mensagem = "Preencha todos os campos.";
        // Chama a função validarTrem() para verificar os dados informados
        } else {
            $mensagem = validarTrem($nome_trem_edicao, $velocidade_maxima_edicao, $tipo_trem_edicao, $id_usuario_edicao, $tipos_trem);
        }

        // Verifica se não existem erros nas validações
        if ($mensagem == "") {
            // Converte a velocidade para o tipo decimal 
            $velocidade_maxima = (float) $velocidade_maxima_edicao;
            // Converte o ID do usuário para o tipo inteiro
            $id_usuario = (int) $id_usuario_edicao;

            // Comando SQL responsável por atualizar os dados do trem selecionado
            // O WHERE identifica qual registro será alterado por meio do ID
            $sql = "UPDATE trem
                    SET nome_trem = ?,
                        velocidade_maxima = ?,
                        tipo_trem = ?,
                        id_usuario = ?
                    WHERE id_trem = ?";

            // Prepara a consulta SQL para receber os novos valores
            $stmt = $conexao->prepare($sql);

            // Associa os valores aos parâmetros da consulta
            // s = string, d = decimal, i = inteiro
            $stmt->bind_param(
                "sdsii",
                $nome_trem_edicao,
                $velocidade_maxima,
                $tipo_trem_edicao,
                $id_usuario,
                $id_trem_edicao
            );

            // Tenta executar a atualização dos dados
            try {
                if ($stmt->execute()) {
                    // Redireciona para a página principal com o parâmetro de sucesso
                    header("Location: visualizar-trem.php?sucesso=editado");
                    exit;
                }
            } catch (mysqli_sql_exception $excecao) {
                // Captura possíveis erros/exceções do MySQL durante a execução da consulta
            }

            // Caso a edição não seja realizada, define uma mensagem de erro
            $mensagem = "Erro ao editar o trem.";
        }

        $tipo_mensagem = "danger";
    }
}

// Consulta os dados dos trens e os nomes dos respectivos responsáveis
// LEFT JOIN permite exibir os trens mesmo quando não possuem um responsável associado
$sql = "SELECT trem.*,
               usuario.nome AS nome_responsavel
        FROM trem
        LEFT JOIN usuario ON trem.id_usuario = usuario.id_usuario";

// Executa a consulta no banco de dados e armazena os registros encontrados
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
                        <a class="nav-link" href="../relatorio/relatorio-geral.php">Relatórios</a>
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
                            <!-- Verifica se a consulta foi executada e se existem trens cadastrados -->
                            <?php if ($resultado && $resultado->num_rows > 0) { ?>
                                <!-- Percorre os registros encontrados no banco de dados -->
                                <!-- Cada registro é armazenado na variável $trem como um array associativo -->
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
                                        <!-- Verifica se o usuário é administrador para exibir os botões de ações -->
                                        <?php if (ehAdministrador()) { ?>
                                            <td class="coluna-acoes">
                                                <!-- Botão que abre o modal de edição do trem selecionado
                                                ("data-*" armazena os dados do trem para que o JavaScript possa preencher o formulário) -->
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
                                                <!-- Botão que chama a função JavaScript abrirAviso(),
                                                enviando o ID do trem e o arquivo responsável pela exclusão -->
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
                            <!-- Se não houver registros, exibe uma mensagem na tabela
                                O colspan utiliza a quantidade de colunas definida anteriormente -->
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

        <!-- Verifica se existe uma mensagem e se nenhum dos modais de cadastro ou edição precisa ser aberto novamente -->
        <?php if ($mensagem != "" && !$abrir_modal && !$abrir_modal_edicao) { ?>
            <!-- Exibe mensagens de sucesso ou erro fora dos modais. -->
            <div class="alert alert-<?= $tipo_mensagem ?> alert-dismissible fade show mt-3" role="alert">
                <?= htmlspecialchars($mensagem) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Fechar"></button>
            </div>
        <?php } ?>
    </div>

    <?php
    // Define a constante TREM_MODAIS para indicar que os arquivos dos modais estão sendo incluídos pela página visualizar-trem.php
    define("TREM_MODAIS", true);
    // Inclui os arquivos responsáveis pelos modais de cadastro, edição e exclusão
    require __DIR__ . "/cadastrar-trem.php";
    require __DIR__ . "/editar-trem.php";
    require __DIR__ . "/excluir-trem.php";
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script/crud.js"></script>

    <!-- MODAL DE CONFIRMAÇÃO DE LOGOUT -->
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
        // Aguarda o carregamento completo do HTML antes de executar o código JavaScript
        document.addEventListener("DOMContentLoaded", function() {
            // Verifica se o modal de cadastro precisa ser aberto novamente
            <?php if ($abrir_modal) { ?>
            // Cria uma instância do modal de cadastro e exibe na tela
                let modalCadastro = new bootstrap.Modal(
                    document.getElementById("modalCadastroTrem")
                );
                modalCadastro.show();
            <?php } ?>

            // Verifica se o modal de edição precisa ser aberto novamente
            <?php if ($abrir_modal_edicao) { ?>
                // Recupera os dados armazenados pelo PHP e preenche os campos do formulário
                // json_encode() transforma os valores PHP em dados compatíveis com JavaScript
                document.getElementById("editar_id_trem").value = <?= json_encode($id_trem_edicao) ?>;
                document.getElementById("editar_nome_trem").value = <?= json_encode($nome_trem_edicao) ?>;
                document.getElementById("editar_velocidade_maxima").value = <?= json_encode($velocidade_maxima_edicao) ?>;
                document.getElementById("editar_tipo_trem").value = <?= json_encode($tipo_trem_edicao) ?>;
                document.getElementById("editar_id_usuario").value = <?= json_encode($id_usuario_edicao) ?>;

                // Cria uma instância do modal de edição e exibe na tela
                let modalEdicao = new bootstrap.Modal(
                    document.getElementById("modalEditarTrem")
                );
                modalEdicao.show();
            <?php } ?>
        });
    </script>
</body>

</html>