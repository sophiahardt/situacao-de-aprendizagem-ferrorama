
<?php
// Verifica se o arquivo foi chamado pela página correta.
if (!defined("SENSOR_MODAIS")) {

    // Inclui as funções de proteção e verifica se o usuário é administrador.
    require_once "../../infra/protecao.php";
    verificarAdministrador();

    // Inclui a conexão com o banco de dados.
    require_once "../../infra/conexao.php";

    // Recebe o ID do sensor enviado pela URL.
    $id_sensor = $_GET["id"] ?? "";

    // Verifica se o ID foi informado, se é um número inteiro válido e se é maior que zero.
    if ($id_sensor == "" || !filter_var($id_sensor, FILTER_VALIDATE_INT) || $id_sensor <= 0) {
        header("Location: visualizar-sensor.php?erro=exclusao");
        exit;
    }

    // Converte o ID para inteiro.
    $id_sensor = (int) $id_sensor;

    // Consulta se existem leituras vinculadas ao sensor.
    $sql = "SELECT id_leitura
            FROM leitura_sensor
            WHERE id_sensor = ?";

    // Prepara a consulta e associa o ID do sensor ao parâmetro.
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_sensor);
    $stmt->execute();

    // Obtém o resultado da consulta.
    $resultado = $stmt->get_result();

    // Verifica se o sensor possui leituras registradas.
    // Caso possua, a exclusão não será permitida.
    if ($resultado->num_rows > 0) {
        header("Location: visualizar-sensor.php?erro=leituras");
        exit;
    }

    // Prepara a consulta para excluir o sensor pelo seu ID.
    $sql = "DELETE FROM sensor WHERE id_sensor = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_sensor);

    // Executa a exclusão e verifica se algum registro foi afetado.
    if ($stmt->execute() && $stmt->affected_rows > 0) {

        // Redireciona para a página com uma mensagem de sucesso.
        header("Location: visualizar-sensor.php?sucesso=1");
        exit;
    } else {

        // Redireciona para a página informando que ocorreu um erro na exclusão.
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

                <!-- Ícone de alerta para indicar que a ação exige atenção -->
                <div style="font-size: 40px;">⚠️</div>

                <!-- Título da confirmação de exclusão -->
                <h4 class="fw-bold mt-2">
                    Deseja continuar?
                </h4>

                <!-- Aviso de que a exclusão não poderá ser desfeita -->
                <p class="fw-bold mb-4">
                    Após a confirmação, não será possível reverter esta ação.
                </p>

                <!-- Botões para cancelar ou confirmar a exclusão -->
                <div class="d-flex justify-content-center gap-5">

                    <!-- Fecha o modal sem realizar a exclusão -->
                    <button type="button"
                        class="btn btn-secondary btn-cancelar px-5"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <!-- Link utilizado para confirmar a exclusão do sensor -->
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
