<?php
if (!defined("RELATORIO_MODAIS")) {
    header("Location: visualizar-relatorio.php?abrir=cadastro");
    exit;
}
?>
<div class="modal fade" id="modalCadastroRelatorio" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="document-text-outline"></ion-icon>
                    Cadastrar novo relatório
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="visualizar-relatorio.php">
                <input type="hidden" name="acao" value="cadastrar">
                <div class="modal-body">
                    <p class="text-muted">Informe os conteúdos do relatório.</p>
                    <?php if ($mensagem != "" && $abrir_modal) { ?>
                        <div class="alert alert-<?= $tipo_mensagem ?>" role="alert">
                            <?= htmlspecialchars($mensagem) ?>
                        </div>
                    <?php } ?>
                    <div class="mb-3">
                        <label for="nome_relatorio" class="form-label">Nome</label>
                        <input type="text" name="nome_relatorio" id="nome_relatorio" class="form-control"
                            placeholder="Digite um nome para o relatório"
                            value="<?= htmlspecialchars($nome_relatorio_form) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_relatorio" class="form-label">Tipo de relatório</label>
                        <select name="tipo_relatorio" id="tipo_relatorio" class="form-select" required>
                            <option value="">Selecione o tipo</option>
                            <option value="Trens" <?= $tipo_relatorio_form == "Trens" ? "selected" : "" ?>>Trens</option>
                            <option value="Sensores" <?= $tipo_relatorio_form == "Sensores" ? "selected" : "" ?>>Sensores</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="data_relatorio" class="form-label">Data do relatório</label>
                        <input type="date" name="data_relatorio" id="data_relatorio" class="form-control"
                            value="<?= htmlspecialchars($data_relatorio_form) ?>" required>
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
