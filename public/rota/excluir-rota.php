<?php

// Verifica se o arquivo não está sendo chamado pelos modais

if (!defined("ROTA_MODAIS")) {

    // Verifica se o usuário está logado e se é administrador

    require_once "../../infra/protecao.php";
    verificarAdministrador();

    // Conecta ao banco de dados

    require_once "../../infra/conexao.php";

     // Recebe o ID da rota pela URL

    $id_rota = $_GET["id"] ?? "";

    // Verifica se o ID foi informado e se é um número inteiro positivo

    if ($id_rota == "" || !filter_var($id_rota, FILTER_VALIDATE_INT) || $id_rota <= 0) {

         // Redireciona caso o ID seja inválido

        header("Location: visualizar-rota.php?erro=exclusao");
        exit;
    }

    // Converte o ID para inteiro

    $id_rota = (int) $id_rota;

     // Verifica se existem sensores cadastrados nessa rota

    $sql = "SELECT id_sensor FROM sensor WHERE id_rota = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_rota);
    $stmt->execute();
    $resultado = $stmt->get_result();

     // Impede a exclusão caso existam sensores vinculados
     
    if ($resultado->num_rows > 0) {
        header("Location: visualizar-rota.php?erro=sensores");
        exit;
    }

    // Inicia uma transação para realizar as alterações no banco

    $conexao->begin_transaction();

    try {

        // Remove o vínculo dos trens com a rota, deixando o ID como NULL

        $sql = "UPDATE trem SET id_rota = NULL WHERE id_rota = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id_rota);
        $stmt->execute();

         // Exclui a rota do banco de dados

        $sql = "DELETE FROM rota WHERE id_rota = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id_rota);
        $stmt->execute();

        // Verifica se a rota foi realmente excluída

        if ($stmt->affected_rows > 0) {

         // Confirma as alterações no banco de dados

            $conexao->commit();

             // Redireciona informando que a exclusão deu certo

            header("Location: visualizar-rota.php?sucesso=1");
            exit;
        }

        // Desfaz as alterações caso nenhuma rota tenha sido excluída

        $conexao->rollback();
    } catch (mysqli_sql_exception $excecao) {

    // Desfaz as alterações caso aconteça algum erro

        $conexao->rollback();
    }

    // Redireciona informando que ocorreu um erro na exclusão

    header("Location: visualizar-rota.php?erro=exclusao");
    exit;
}
?>

<!-- Modal de confirmação para excluir uma rota -->

<div class="modal" id="modalExclusao" tabindex="-1">

  <!-- Define o posicionamento do modal -->

    <div class="modal-dialog modal-dialog-centered">

      <!-- Conteúdo principal do modal -->

        <div class="modal-content text-center">
            <div class="modal-body p-4">

              <!-- Ícone de alerta -->

                <div style="font-size: 40px;">⚠️</div>

                 <!-- Título da confirmação -->

                <h4 class="fw-bold mt-2">
                    Deseja continuar?
                </h4>

                    <!-- Mensagem de alerta -->

                <p class="fw-bold mb-4">
                    Após a confirmação, não será possível reverter esta ação.
                </p>

                <!-- Área dos botões -->

                <div class="d-flex justify-content-center gap-5">

                     <!-- Botão que fecha o modal sem excluir -->

                    <button type="button"
                        class="btn btn-secondary btn-cancelar px-5"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    
                     <!-- Botão que confirma a exclusão da rota -->

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