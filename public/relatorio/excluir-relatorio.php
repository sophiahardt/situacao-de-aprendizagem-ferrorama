<?php
if (!defined("RELATORIO_MODAIS")) {
    session_start();
    require_once "../../infra/conexao.php";
    require_once "../../infra/protecao.php";
    verificarAdministrador();

    if (!isset($_GET["id"]) || !filter_var($_GET["id"], FILTER_VALIDATE_INT) || $_GET["id"] <= 0) {
        header("Location: visualizar-relatorio.php");
        exit;
    }

    $id = (int) $_GET["id"];
    $sql = "DELETE FROM relatorio WHERE id_relatorio = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        header("Location: visualizar-relatorio.php?sucesso=1");
        exit;
    }

    header("Location: visualizar-relatorio.php?erro=exclusao");
    exit;
}
?>
<div class="modal" id="modalExclusao" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body p-4">
                <div style="font-size: 40px;">⚠️</div>
                <h4 class="fw-bold mt-2">Deseja continuar?</h4>
                <p class="fw-bold mb-4">
                    Após a confirmação, não será possível reverter esta ação.
                </p>
                <div class="d-flex justify-content-center gap-5">
                    <button type="button" class="btn btn-secondary btn-cancelar px-5" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <a id="btnConfirmarExclusao" href="#" class="btn btn-danger px-5">Excluir</a>
                </div>
            </div>
        </div>
    </div>
</div>
