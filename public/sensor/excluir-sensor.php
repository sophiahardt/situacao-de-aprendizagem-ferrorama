<?php
if (!defined("SENSOR_MODAIS")) {
    require_once "../../infra/protecao.php";
    verificarAdministrador();
    require_once "../../infra/conexao.php";

    $id_sensor = $_GET["id"] ?? "";

    if ($id_sensor == "" || !filter_var($id_sensor, FILTER_VALIDATE_INT) || $id_sensor <= 0) {
        header("Location: visualizar-sensor.php?erro=exclusao");
        exit;
    }

    $id_sensor = (int) $id_sensor;

    $sql = "SELECT id_leitura
            FROM leitura_sensor
            WHERE id_sensor = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_sensor);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        header("Location: visualizar-sensor.php?erro=leituras");
        exit;
    }

    $sql = "DELETE FROM sensor WHERE id_sensor = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_sensor);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        header("Location: visualizar-sensor.php?sucesso=1");
        exit;
    } else {
        header("Location: visualizar-sensor.php?erro=exclusao");
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