<?php
if (!defined("RELATORIO_MODAIS")) {
    $id_relatorio = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    if (!$id_relatorio || $id_relatorio <= 0) {
        header("Location: visualizar-relatorio.php");
    } else {
        header("Location: visualizar-relatorio.php?editar=" . $id_relatorio);
    }
    exit;
}
?>
<div class="modal fade" id="modalEditarRelatorio" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="pencil-outline"></ion-icon>
                    Editar relatório
                </h5>

            </div>
            <form method="POST" action="visualizar-relatorio.php">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id_relatorio" id="editar_id_relatorio">
                <div class="modal-body">
                    <p class="text-muted">Altere as informações do relatório selecionado.</p>
                    <?php if ($mensagem != "" && $abrir_modal_edicao) { ?>
                        <div class="alert alert-<?= $tipo_mensagem ?>" role="alert">
                            <?= htmlspecialchars($mensagem) ?>
                        </div>
                    <?php } ?>
                    <div class="mb-3">
                        <label for="editar_nome_relatorio" class="form-label">Nome do relatório</label>
                        <input type="text" name="nome_relatorio" id="editar_nome_relatorio" class="form-control"
                            value="<?= htmlspecialchars($nome_relatorio_edicao) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar_tipo_relatorio" class="form-label">Tipo de relatório</label>
                        <select name="tipo_relatorio" id="editar_tipo_relatorio" class="form-select" required>
                            <option value="Trens" <?= $tipo_relatorio_edicao == "Trens" ? "selected" : "" ?>>Trens</option>
                            <option value="Sensores" <?= $tipo_relatorio_edicao == "Sensores" ? "selected" : "" ?>>Sensores</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editar_data_relatorio" class="form-label">Data do relatório</label>
                        <input type="date" name="data_relatorio" id="editar_data_relatorio" class="form-control"
                            value="<?= htmlspecialchars($data_relatorio_edicao) ?>" required>
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
