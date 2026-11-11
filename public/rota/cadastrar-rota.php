<?php
if (!defined("ROTA_MODAIS")) {
    header("Location: visualizar-rota.php");
    exit;
}
?>
<!-- MODAL DE CADASTRO -->
<div class="modal fade" id="modalCadastroRota" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="map-outline"></ion-icon>
                    Cadastrar nova rota
                </h5>

            </div>
            <form method="POST" action="visualizar-rota.php">
                <input type="hidden" name="acao" value="cadastrar">
                <div class="modal-body">
                    <p class="text-muted">
                        Preencha as informações para cadastrar uma nova rota no sistema.
                    </p>
                    <?php if ($mensagem != "" && $abrir_modal) { ?>
                        <div class="alert alert-<?= $tipo_mensagem ?>" role="alert">
                            <?= htmlspecialchars($mensagem) ?>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nome da Rota</label>
                                <input type="text"
                                    name="nome_rota"
                                    class="form-control"
                                    placeholder="Informe o nome da rota"
                                    maxlength="45"
                                    value="<?= htmlspecialchars($nome_rota_form) ?>"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Extensão (Km)</label>
                                <input type="number"
                                    name="extensao"
                                    class="form-control"
                                    placeholder="Ex: 45.50"
                                    step="0.01"
                                    min="0.01"
                                    value="<?= htmlspecialchars($extensao_form) ?>"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tempo estimado (Min)</label>
                                <input type="number"
                                    name="tempo_estimado_minutos"
                                    class="form-control"
                                    placeholder="Ex: 45"
                                    min="1"
                                    value="<?= htmlspecialchars($tempo_estimado_form) ?>"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Trem</label>
                                <select name="id_trem" class="form-select" required>
                                    <option value="" disabled <?= $id_trem_form == "" ? "selected" : "" ?>>
                                        Selecione o trem que anda nesta rota
                                    </option>
                                    <?php foreach ($trens as $trem) { ?>
                                        <option value="<?= $trem["id_trem"] ?>"
                                            <?= $id_trem_form == $trem["id_trem"] ? "selected" : "" ?>>
                                            <?= htmlspecialchars($trem["nome_trem"]) ?>
                                            <?= $trem["nome_rota"] ? "(hoje na rota " . htmlspecialchars($trem["nome_rota"]) . ")" : "(sem rota)" ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <div class="form-text">
                                    <?php if (count($trens) == 0) { ?>
                                        Nenhum trem cadastrado.
                                        <a href="../trem/visualizar-trem.php?abrir=cadastro" target="_blank" rel="noopener noreferrer">
                                            Cadastrar trem
                                        </a>
                                    <?php } else { ?>
                                        Se o trem já andar em outra rota, ele passa para esta.
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Descrição</label>
                                <textarea name="descricao"
                                    class="form-control"
                                    rows="8"
                                    placeholder="Descreva a rota (trajeto, estações, observações...)"
                                    required><?= htmlspecialchars($descricao_form) ?></textarea>
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