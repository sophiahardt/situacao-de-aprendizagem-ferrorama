<?php
if (!defined("ROTA_MODAIS")) {
    require_once "../../infra/protecao.php";
    verificarAdministrador();
    require_once "../../infra/conexao.php";

    $id_rota = $_GET["id"] ?? "";

    if ($id_rota == "" || !filter_var($id_rota, FILTER_VALIDATE_INT) || $id_rota <= 0) {
        header("Location: visualizar-rota.php?erro=exclusao");
        exit;
    }

    $id_rota = (int) $id_rota;

    $sql = "SELECT id_sensor FROM sensor WHERE id_rota = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_rota);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        header("Location: visualizar-rota.php?erro=sensores");
        exit;
    }

    $conexao->begin_transaction();

    try {
        $sql = "UPDATE trem SET id_rota = NULL WHERE id_rota = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id_rota);
        $stmt->execute();

        $sql = "DELETE FROM rota WHERE id_rota = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id_rota);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $conexao->commit();
            header("Location: visualizar-rota.php?sucesso=1");
            exit;
        }

        $conexao->rollback();
    } catch (mysqli_sql_exception $excecao) {
        $conexao->rollback();
    }

    header("Location: visualizar-rota.php?erro=exclusao");
    exit;
}
?>
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