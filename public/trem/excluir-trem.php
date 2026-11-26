<?php
// Verifica se a constante TREM_MODAIS não foi definida
if (!defined("TREM_MODAIS")) {
    require_once "../../infra/conexao.php";
    require_once "../../infra/protecao.php";
    verificarAdministrador();

    // Recupera o ID do trem enviado pela URL através do método GET
    // Caso o parâmetro "id" não exista, atribui uma string vazia
    $id_trem = $_GET["id"] ?? "";

    // Verifica se o ID está vazio, não é um número inteiro válido ou é menor ou igual a zero
    if ($id_trem == "" || !filter_var($id_trem, FILTER_VALIDATE_INT) || $id_trem <= 0) {
        // Redireciona para a página de visualização dos trens com uma mensagem de erro
        header("Location: visualizar-trem.php?erro=exclusao");
        exit;
    }

    // Converte o ID recebido para o tipo inteiro
    $id_trem = (int) $id_trem;

    // Consulta se existem sensores vinculados ao trem que será excluído
    $sql_sensor = "SELECT id_sensor FROM sensor WHERE id_trem = ?";
    // Prepara a consulta SQL para receber o ID do trem
    $stmt_sensor = $conexao->prepare($sql_sensor);
    // Substitui o ? pelo ID do trem
    $stmt_sensor->bind_param("i", $id_trem);
    // Executa a consulta no banco de dados
    $stmt_sensor->execute();
    // Recupera o resultado da consulta
    $resultado_sensor = $stmt_sensor->get_result();

    // Verifica se foi encontrado algum sensor vinculado ao trem
    if ($resultado_sensor->num_rows > 0) {
        // Impede a exclusão e redireciona com uma mensagem de erro
        header("Location: visualizar-trem.php?erro=sensores");
        exit;
    }

    // Consulta se o trem possui uma rota vinculada
    // id_rota IS NOT NULL verifica se o campo da rota não está vazio
    $sql_rota = "SELECT id_trem FROM trem WHERE id_trem = ? AND id_rota IS NOT NULL";
    // Prepara a consulta SQL 
    $stmt_rota = $conexao->prepare($sql_rota);
    // Substitui o ? pelo ID do trem
    $stmt_rota->bind_param("i", $id_trem);
    // Executa a consulta no banco de dados
    $stmt_rota->execute();
    // Recupera o resultado da consulta
    $resultado_rota = $stmt_rota->get_result();

    // Verifica se o trem está associado a alguma rota
    if ($resultado_rota->num_rows > 0) {
        // Impede a exclusão e redireciona com uma mensagem de erro
        header("Location: visualizar-trem.php?erro=rotas");
        exit;
    }

    // Consulta SQL responsável por excluir o trem pelo seu ID
    $sql = "DELETE FROM trem WHERE id_trem = ?";
    // Prepara a consulta para receber o ID do trem
    $stmt = $conexao->prepare($sql);
    // Substitui o ? pelo ID do trem
    $stmt->bind_param("i", $id_trem);

    // Executa a exclusão e verifica se pelo menos um registro foi afetado
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        // Se a exclusão for realizada, redireciona com uma mensagem de sucesso
        header("Location: visualizar-trem.php?sucesso=1");
        exit;
    } else {
        // Caso a exclusão falhe ou nenhum registro seja encontrado, redireciona com erro
        header("Location: visualizar-trem.php?erro=exclusao");
        exit;
    }
}
?>
<!-- MODAL DE CONFIRMAÇÃO DE EXCLUSÃO -->
<div class="modal" id="modalExclusao" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body p-4">
                <div style="font-size: 40px;">⚠️</div>
                <h4 class="fw-bold mt-2">
                    Deseja continuar?
                </h4>
                <p class="fw-bold mb-4">
                    Após a confirmação, não será possível reverter esta ação.
                </p>
                <div class="d-flex justify-content-center gap-5">
                    <button type="button"
                        class="btn btn-secondary btn-cancelar px-5"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <!-- Link utilizado pelo JavaScript para confirmar a exclusão do trem -->
                    <a id="btnConfirmarExclusao"
                        href="#"
                        class="btn btn-danger px-5">
                        Excluir
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>