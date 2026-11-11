<?php
if (!defined("ROTA_MODAIS")) {
    header("Location: visualizar-rota.php");
    exit;
}
?>
<div class="modal fade" id="modalEditarRota" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="pencil-outline"></ion-icon>
                    Editar rota
                </h5>

            </div>
            <form method="POST" action="visualizar-rota.php">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id_rota" id="editar_id_rota">
                <div class="modal-body">
                    <p class="text-muted">
                        Altere as informações da rota selecionada.
                    </p>
                    <?php if ($mensagem != "" && $abrir_modal_edicao) { ?>
                        <div class="alert alert-<?= $tipo_mensagem ?>" role="alert">
                            <?= htmlspecialchars($mensagem) ?>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editar_nome_rota" class="form-label">Nome da Rota</label>
                                <input type="text"
                                    name="nome_rota"
                                    id="editar_nome_rota"
                                    class="form-control"
                                    placeholder="Informe o nome da rota"
                                    maxlength="45"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="editar_extensao" class="form-label">Extensão (Km)</label>
                                <input type="number"
                                    name="extensao"
                                    id="editar_extensao"
                                    class="form-control"
                                    placeholder="Ex: 45.50"
                                    step="0.01"
                                    min="0.01"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="editar_tempo_estimado_minutos" class="form-label">Tempo estimado (Min)</label>
                                <input type="number"
                                    name="tempo_estimado_minutos"
                                    id="editar_tempo_estimado_minutos"
                                    class="form-control"
                                    placeholder="Ex: 45"
                                    min="1"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="editar_id_trem" class="form-label">Trem</label>
                                <select name="id_trem" id="editar_id_trem" class="form-select" required>
                                    <option value="" disabled>Selecione o trem que anda nesta rota</option>
                                    <?php foreach ($trens as $trem) { ?>
                                        <option value="<?= $trem["id_trem"] ?>">
                                            <?= htmlspecialchars($trem["nome_trem"]) ?>
                                            <?= $trem["nome_rota"] ? "(hoje na rota " . htmlspecialchars($trem["nome_rota"]) . ")" : "(sem rota)" ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <div class="form-text">
                                    Ao trocar o trem, o anterior fica sem rota. Se o trem escolhido
                                    estiver em outra rota, ele passa para esta.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editar_descricao" class="form-label">Descrição</label>
                                <textarea name="descricao"
                                    id="editar_descricao"
                                    class="form-control"
                                    rows="8"
                                    placeholder="Descreva a rota (trajeto, estações, observações...)"
                                    required></textarea>
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
                        Salvar alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>