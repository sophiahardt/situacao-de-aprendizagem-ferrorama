
<?php
// Verifica se o arquivo foi chamado pela página correta.
// Caso contrário, redireciona para a página de visualização dos sensores.
if (!defined("SENSOR_MODAIS")) {
    header("Location: visualizar-sensor.php");
    exit;
}
?>

<!-- MODAL DE CADASTRO -->
<div class="modal fade" id="modalCadastroSensor" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- Cabeçalho do modal -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="hardware-chip-outline"></ion-icon>
                    Cadastrar novo sensor
                </h5>
            </div>

            <!-- Formulário para enviar os dados do sensor -->
            <form method="POST" action="visualizar-sensor.php">

                <!-- Identifica que a ação realizada será um cadastro -->
                <input type="hidden" name="acao" value="cadastrar">

                <div class="modal-body">

                    <!-- Orientação para o preenchimento do formulário -->
                    <p class="text-muted">
                        Preencha as informações para cadastrar um novo sensor no sistema.
                    </p>

                    <!-- Exibe uma mensagem de sucesso ou erro quando necessário -->
                    <?php if ($mensagem != "" && $abrir_modal) { ?>
                        <div class="alert alert-<?= $tipo_mensagem ?>" role="alert">
                            <?= htmlspecialchars($mensagem) ?>
                        </div>
                    <?php } ?>

                    <div class="row">

                        <!-- Primeira coluna do formulário -->
                        <div class="col-md-6">

                            <!-- Campo para informar o nome do sensor -->
                            <div class="mb-3">
                                <label class="form-label">Nome do Sensor</label>
                                <input type="text"
                                    name="nome_sensor"
                                    class="form-control"
                                    placeholder="Informe o nome do sensor"
                                    value="<?= htmlspecialchars($nome_sensor_form) ?>"
                                    required>
                            </div>

                            <!-- Seleção do local onde o sensor será instalado -->
                            <div class="mb-3">
                                <label class="form-label">Localização do Sensor</label>
                                <div class="row">

                                    <!-- Opção para associar o sensor a uma rota -->
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

                                    <!-- Opção para associar o sensor a um trem -->
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

                            <!-- Campo para selecionar uma rota cadastrada -->
                            <div class="mb-3" id="campo_rota" style="display: none;">
                                <label for="id_rota" class="form-label">Rota</label>
                                <select name="id_localizacao" id="id_rota" class="form-select" disabled>
                                    <option value="">Selecione a rota</option>

                                    <!-- Percorre as rotas cadastradas e exibe as opções -->
                                    <?php foreach ($rotas as $rota) { ?>
                                        <option value="<?= $rota["id_rota"] ?>"
                                            <?= $id_localizacao_form == $rota["id_rota"] && $localizacao_form == "rota" ? "selected" : "" ?>>
                                            <?= htmlspecialchars($rota["nome_rota"]) ?>
                                        </option>
                                    <?php } ?>
                                </select>

                                <!-- Informa se não existem rotas cadastradas -->
                                <div class="form-text">
                                    <?php if (count($rotas) == 0) { ?>
                                        Nenhuma rota cadastrada.
                                        <a href="../rota/visualizar-rota.php?abrir=cadastro" target="_blank" rel="noopener noreferrer">
                                            Cadastrar rota
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Campo para selecionar um trem cadastrado -->
                            <div class="mb-3" id="campo_trem" style="display: none;">
                                <label for="id_trem" class="form-label">Trem</label>
                                <select name="id_localizacao" id="id_trem" class="form-select" disabled>
                                    <option value="">Selecione o trem</option>

                                    <!-- Percorre os trens cadastrados e exibe as opções -->
                                    <?php foreach ($trens as $trem) { ?>
                                        <option value="<?= $trem["id_trem"] ?>"
                                            <?= $id_localizacao_form == $trem["id_trem"] && $localizacao_form == "trem" ? "selected" : "" ?>>
                                            <?= htmlspecialchars($trem["nome_trem"]) ?>
                                        </option>
                                    <?php } ?>
                                </select>

                                <!-- Informa se não existem trens cadastrados -->
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

                        <!-- Segunda coluna do formulário -->
                        <div class="col-md-6">

                            <!-- Campo para selecionar o tipo de informação coletada pelo sensor -->
                            <div class="mb-3">
                                <label class="form-label">Tipo de Dado</label>
                                <select name="tipo_sensor" class="form-select" required>
                                    <option value="" disabled <?= $tipo_sensor_form == "" ? "selected" : "" ?>>
                                        Selecione o tipo de dado coletado
                                    </option>

                                    <!-- Opções de dados que o sensor pode coletar -->
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

                <!-- Rodapé do modal com os botões de ação -->
                <div class="modal-footer">

                    <!-- Fecha o modal sem enviar o formulário -->
                    <button type="button" class="btn btn-light btn-cancelar" data-bs-dismiss="modal">
                        <ion-icon name="close-outline"></ion-icon>
                        Cancelar
                    </button>

                    <!-- Envia os dados para realizar o cadastro -->
                    <button type="submit" class="btn btn-primary btn-login px-5">
                        <ion-icon name="save-outline"></ion-icon>
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
