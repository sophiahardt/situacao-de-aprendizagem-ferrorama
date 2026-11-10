<?php
if (!defined("TREM_MODAIS")) {
    header("Location: visualizar-trem.php");
    exit;
}
?>
<!-- MODAL DE EDIÇÃO -->
<div class="modal fade" id="modalEditarTrem" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="pencil-outline"></ion-icon>
                    Editar trem
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="visualizar-trem.php">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id_trem" id="editar_id_trem">
                <div class="modal-body">
                    <p class="text-muted">
                        Altere as informações do trem selecionado.
                    </p>
                    <?php if ($mensagem != "" && $abrir_modal_edicao) { ?>
                        <div class="alert alert-<?= $tipo_mensagem ?>" role="alert">
                            <?= htmlspecialchars($mensagem) ?>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editar_nome_trem" class="form-label">Nome do Trem</label>
                                <input type="text"
                                    name="nome_trem"
                                    id="editar_nome_trem"
                                    class="form-control"
                                    placeholder="Informe o nome do trem"
                                    maxlength="100"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="editar_velocidade_maxima" class="form-label">Velocidade Máxima</label>
                                <input type="number"
                                    name="velocidade_maxima"
                                    id="editar_velocidade_maxima"
                                    class="form-control"
                                    placeholder="Informe a velocidade máxima do trem"
                                    min="0.01"
                                    max="999.99"
                                    step="0.01"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editar_tipo_trem" class="form-label">Tipo de Trem</label>
                                <select name="tipo_trem" id="editar_tipo_trem" class="form-select" required>
                                    <option value="" disabled>Selecione o tipo de trem</option>
                                    <?php foreach ($tipos_trem as $tipo) { ?>
                                        <option value="<?= $tipo ?>"><?= $tipo ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editar_id_usuario" class="form-label">Responsável</label>
                                <select name="id_usuario" id="editar_id_usuario" class="form-select" required>
                                    <option value="" disabled>Selecione o responsável</option>
                                    <?php foreach ($usuarios as $usuario) { ?>
                                        <option value="<?= $usuario["id_usuario"] ?>">
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
                        Salvar alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>