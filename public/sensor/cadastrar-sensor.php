<?php
if (!defined("SENSOR_MODAIS")) {
    header("Location: visualizar-sensor.php");
    exit;
}
?>
    <!-- MODAL DE CADASTRO -->
    <div class="modal fade" id="modalCadastroSensor" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <ion-icon name="hardware-chip-outline"></ion-icon>
                        Cadastrar novo sensor
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="visualizar-sensor.php">
                    <input type="hidden" name="acao" value="cadastrar">
                    <div class="modal-body">
                        <p class="text-muted">
                            Preencha as informações para cadastrar um novo sensor no sistema.
                        </p>
                        <?php if ($mensagem != "" && $abrir_modal) { ?>
                            <div class="alert alert-<?= $tipo_mensagem ?>" role="alert">
                                <?= htmlspecialchars($mensagem) ?>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nome do Sensor</label>
                                    <input type="text"
                                        name="nome_sensor"
                                        class="form-control"
                                        placeholder="Informe o nome do sensor"
                                        value="<?= htmlspecialchars($nome_sensor_form) ?>"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Localização do Sensor</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="radio"
                                                class="btn-check"
                                                name="localizacao"
                                                id="rota"
                                                value="rota"
                                                onchange="verificarLocalizacao()"
                                                <?= $localizacao_form == "rota" ? "checked" : "" ?>
                                                required>
                                            <label class="btn btn-outline-dark w-100" for="rota">
                                                Rota
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="radio"
                                                class="btn-check"
                                                name="localizacao"
                                                id="trem"
                                                value="trem"
                                                onchange="verificarLocalizacao()"
                                                <?= $localizacao_form == "trem" ? "checked" : "" ?>>
                                            <label class="btn btn-outline-dark w-100" for="trem">
                                                Trem
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3" id="campo_rota" style="display: none;">
                                    <label for="id_rota" class="form-label">Rota</label>
                                    <select name="id_localizacao" id="id_rota" class="form-select" disabled>
                                        <option value="">Selecione a rota</option>
                                        <?php foreach ($rotas as $rota) { ?>
                                            <option value="<?= $rota["id_rota"] ?>"
                                                <?= $id_localizacao_form == $rota["id_rota"] && $localizacao_form == "rota" ? "selected" : "" ?>>
                                                <?= htmlspecialchars($rota["nome_rota"]) ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <div class="form-text">
                                        <?php if (count($rotas) == 0) { ?>
                                            Nenhuma rota cadastrada.
                                            <a href="../rota/visualizar-rota.php?abrir=cadastro" target="_blank" rel="noopener noreferrer">
                                                Cadastrar rota
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="mb-3" id="campo_trem" style="display: none;">
                                    <label for="id_trem" class="form-label">Trem</label>
                                    <select name="id_localizacao" id="id_trem" class="form-select" disabled>
                                        <option value="">Selecione o trem</option>
                                        <?php foreach ($trens as $trem) { ?>
                                            <option value="<?= $trem["id_trem"] ?>"
                                                <?= $id_localizacao_form == $trem["id_trem"] && $localizacao_form == "trem" ? "selected" : "" ?>>
                                                <?= htmlspecialchars($trem["nome_trem"]) ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <div class="form-text">
                                        <?php if (count($trens) == 0) { ?>
                                            Nenhum trem cadastrado.
                                            <a href="../trem/visualizar-trem.php?abrir=cadastro" target="_blank" rel="noopener noreferrer">
                                                Cadastrar trem
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Dado</label>
                                    <select name="tipo_sensor" class="form-select" required>
                                        <option value="" disabled <?= $tipo_sensor_form == "" ? "selected" : "" ?>>
                                            Selecione o tipo de dado coletado
                                        </option>
                                        <option value="Temperatura" <?= $tipo_sensor_form == "Temperatura" ? "selected" : "" ?>>
                                            Temperatura
                                        </option>
                                        <option value="Velocidade" <?= $tipo_sensor_form == "Velocidade" ? "selected" : "" ?>>
                                            Velocidade
                                        </option>
                                        <option value="Presença" <?= $tipo_sensor_form == "Presença" ? "selected" : "" ?>>
                                            Presença
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-cancelar" data-bs-dismiss="modal">
                            <ion-icon name="close-outline"></ion-icon>
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary btn-login px-5">
                            <ion-icon name="save-outline"></ion-icon>
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>