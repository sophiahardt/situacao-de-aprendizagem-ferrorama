<?php
// Verifica se a constante TREM_MODAIS foi definida
if (!defined("TREM_MODAIS")) {
    // Redireciona o usuário para a página de visualização dos trens caso não tenha sido definida
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
                <!-- Campo oculto que identifica a ação de cadastro -->
                <input type="hidden" name="acao" value="cadastrar">
                <div class="modal-body">
                    <p class="text-muted">
                        Preencha as informações para cadastrar um novo trem no sistema.
                    </p>

                    <!-- Verifica se existe uma mensagem e se o modal deve ser aberto -->
                    <?php if ($mensagem != "" && $abrir_modal) { ?>
                    <!-- Exibe uma mensagem de sucesso ou erro (o tipo da mensagem define a cor do alerta) -->
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
                                    <!-- Verifica se nenhum tipo foi selecionado para deixar a opção inicial selecionada -->
                                    <option value="" disabled <?= $tipo_trem_form == "" ? "selected" : "" ?>>
                                        Selecione o tipo de trem
                                    </option>
                                    <!-- Percorre o array $tipos_trem para criar uma opção para cada tipo de trem disponível -->
                                    <?php foreach ($tipos_trem as $tipo) { ?>
                                    <!-- Mantém selecionado o tipo escolhido pelo usuário -->
                                        <option value="<?= $tipo ?>" <?= $tipo_trem_form == $tipo ? "selected" : "" ?>>
                                            <?= $tipo ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Responsável</label>
                                <select name="id_usuario" class="form-select" required>
                                    <!-- Se nenhum responsável estiver selecionado, mantém a opção inicial como selecionada -->
                                    <option value="" disabled <?= $id_usuario_form == "" ? "selected" : "" ?>>
                                        Selecione o responsável
                                    </option>
                                    <!-- Percorre o array $usuarios para listar os usuários disponíveis como responsáveis -->
                                    <?php foreach ($usuarios as $usuario) { ?>
                                    <!-- Compara o ID do usuário com o ID anteriormente selecionado para manter a opção. -->
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