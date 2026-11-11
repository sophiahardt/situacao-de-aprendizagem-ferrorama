<?php
if (!defined("TREM_MODAIS")) {
    header("Location: visualizar-trem.php");
    exit;
}
?>
<!-- MODAL DE CADASTRO -->
<div class="modal fade" id="modalCadastroTrem" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="train-outline"></ion-icon>
                    Cadastrar novo trem
                </h5>

            </div>
            <form method="POST" action="visualizar-trem.php">
                <input type="hidden" name="acao" value="cadastrar">
                <div class="modal-body">
                    <p class="text-muted">
                        Preencha as informações para cadastrar um novo trem no sistema.
                    </p>
                    <?php if ($mensagem != "" && $abrir_modal) { ?>
                        <div class="alert alert-<?= $tipo_mensagem ?>" role="alert">
                            <?= htmlspecialchars($mensagem) ?>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nome do Trem</label>
                                <input type="text"
                                    name="nome_trem"
                                    class="form-control"
                                    placeholder="Informe o nome do trem"
                                    maxlength="100"
                                    value="<?= htmlspecialchars($nome_trem_form) ?>"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Velocidade Máxima</label>
                                <input type="number"
                                    name="velocidade_maxima"
                                    class="form-control"
                                    placeholder="Informe a velocidade máxima do trem"
                                    min="0.01"
                                    max="999.99"
                                    step="0.01"
                                    value="<?= htmlspecialchars($velocidade_maxima_form) ?>"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Trem</label>
                                <select name="tipo_trem" class="form-select" required>
                                    <option value="" disabled <?= $tipo_trem_form == "" ? "selected" : "" ?>>
                                        Selecione o tipo de trem
                                    </option>
                                    <?php foreach ($tipos_trem as $tipo) { ?>
                                        <option value="<?= $tipo ?>" <?= $tipo_trem_form == $tipo ? "selected" : "" ?>>
                                            <?= $tipo ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Responsável</label>
                                <select name="id_usuario" class="form-select" required>
                                    <option value="" disabled <?= $id_usuario_form == "" ? "selected" : "" ?>>
                                        Selecione o responsável
                                    </option>
                                    <?php foreach ($usuarios as $usuario) { ?>
                                        <option value="<?= $usuario["id_usuario"] ?>"
                                            <?= $id_usuario_form == $usuario["id_usuario"] ? "selected" : "" ?>>
                                            <?= htmlspecialchars($usuario["nome"]) ?>
                                        </option>
                                    <?php } ?>
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