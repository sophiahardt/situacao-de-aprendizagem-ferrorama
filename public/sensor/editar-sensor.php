
<?php
// Verifica se o arquivo foi chamado pela página correta.
// Caso contrário, redireciona para a página de visualização dos sensores.
if (!defined("SENSOR_MODAIS")) {
    header("Location: visualizar-sensor.php");
    exit;
}
?>

<!-- MODAL DE EDIÇÃO -->
<div class="modal fade" id="modalEditarSensor" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- Cabeçalho do modal -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="pencil-outline"></ion-icon>
                    Editar sensor
                </h5>
            </div>

            <!-- Formulário para enviar as alterações do sensor -->
            <form method="POST" action="visualizar-sensor.php">

                <!-- Identifica que a ação realizada será uma edição -->
                <input type="hidden" name="acao" value="editar">

                <!-- Armazena o ID do sensor que será editado -->
                <input type="hidden" name="id_sensor" id="editar_id_sensor">

                <div class="modal-body">

                    <!-- Orientação para a edição das informações -->
                    <p class="text-muted">
                        Altere as informações do sensor selecionado.
                    </p>

                    <!-- Exibe uma mensagem de sucesso ou erro quando necessário -->
                    <?php if ($mensagem != "" && $abrir_modal_edicao) { ?>
                        <div class="alert alert-<?= $tipo_mensagem ?>" role="alert">
                            <?= htmlspecialchars($mensagem) ?>
                        </div>
                    <?php } ?>

                    <div class="row">

                        <!-- Primeira coluna do formulário -->
                        <div class="col-md-6">

                            <!-- Campo para editar o nome do sensor -->
                            <div class="mb-3">
                                <label for="editar_nome_sensor" class="form-label">
                                    Nome do Sensor
                                </label>
                                <input type="text"
                                    name="nome_sensor"
                                    id="editar_nome_sensor"
                                    class="form-control"
                                    placeholder="Informe o nome do sensor"
                                    required>
                            </div>

                            <!-- Seleção do local onde o sensor está instalado -->
                            <div class="mb-3">
                                <label class="form-label">Localização do Sensor</label>
                                <div class="row">

                                    <!-- Opção para associar o sensor a uma rota -->
                                    <div class="col-md-6">
                                        <input type="radio"
                                            class="btn-check"
                                            name="localizacao"
                                            id="editar_rota"
                                            value="rota"
                                            onchange="verificarLocalizacaoEdicao()"
                                            required>
                                        <label class="btn btn-outline-dark w-100" for="editar_rota">
                                            Rota
                                        </label>
                                    </div>

                                    <!-- Opção para associar o sensor a um trem -->
                                    <div class="col-md-6">
                                        <input type="radio"
                                            class="btn-check"
                                            name="localizacao"
                                            id="editar_trem"
                                            value="trem"
                                            onchange="verificarLocalizacaoEdicao()">
                                        <label class="btn btn-outline-dark w-100" for="editar_trem">
                                            Trem
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Campo para selecionar uma rota cadastrada -->
                            <div class="mb-3" id="campo_rota_edicao" style="display: none;">
                                <label for="editar_id_rota" class="form-label">Rota</label>
                                <select name="id_localizacao" id="editar_id_rota" class="form-select" disabled>
                                    <option value="">Selecione a rota</option>

                                    <!-- Percorre as rotas cadastradas e exibe as opções -->
                                    <?php foreach ($rotas as $rota) { ?>
                                        <option value="<?= $rota["id_rota"] ?>">
                                            <?= htmlspecialchars($rota["nome_rota"]) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <!-- Campo para selecionar um trem cadastrado -->
                            <div class="mb-3" id="campo_trem_edicao" style="display: none;">
                                <label for="editar_id_trem" class="form-label">Trem</label>
                                <select name="id_localizacao" id="editar_id_trem" class="form-select" disabled>
                                    <option value="">Selecione o trem</option>

                                    <!-- Percorre os trens cadastrados e exibe as opções -->
                                    <?php foreach ($trens as $trem) { ?>
                                        <option value="<?= $trem["id_trem"] ?>">
                                            <?= htmlspecialchars($trem["nome_trem"]) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <!-- Segunda coluna do formulário -->
                        <div class="col-md-6">

                            <!-- Campo para alterar o tipo de dado coletado pelo sensor -->
                            <div class="mb-3">
                                <label for="editar_tipo_sensor" class="form-label">
                                    Tipo de Dado
                                </label>
                                <select name="tipo_sensor" id="editar_tipo_sensor" class="form-select" required>

                                    <!-- Opções de dados que o sensor pode coletar -->
                                    <option value="">Selecione o tipo de dado coletado</option>
                                    <option value="Temperatura">Temperatura</option>
                                    <option value="Velocidade">Velocidade</option>
                                    <option value="Presença">Presença</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rodapé do modal com os botões de ação -->
                <div class="modal-footer">

                    <!-- Fecha o modal sem salvar as alterações -->
                    <button type="button" class="btn btn-light btn-cancelar" data-bs-dismiss="modal">
                        <ion-icon name="close-outline"></ion-icon>
                        Cancelar
                    </button>

                    <!-- Envia o formulário para salvar as alterações do sensor -->
                    <button type="submit" class="btn btn-primary btn-login px-5">
                        <ion-icon name="save-outline"></ion-icon>
                        Salvar alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
