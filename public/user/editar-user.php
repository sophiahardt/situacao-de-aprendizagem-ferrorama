<?php
if (!defined("USUARIO_MODAIS")) {
    header("Location: visualizar-user.php");
    exit;
}
?>
<!-- MODAL DE EDIÇÃO -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="pencil-outline"></ion-icon>
                    Editar usuário
                </h5>
            </div>
            <form method="POST" action="visualizar-user.php" autocomplete="off" class="d-flex flex-column overflow-hidden">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id_usuario" id="editar_id_usuario">
                <div class="modal-body">
                    <p class="text-muted">
                        Altere os dados do usuário selecionado.
                    </p>
                    <?php if ($mensagem != "" && $abrir_modal_edicao) { ?>
                        <div class="alert alert-<?= $tipo_mensagem ?>" role="alert">
                            <?= htmlspecialchars($mensagem) ?>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editar_nome" class="form-label">Nome Completo</label>
                                <input type="text"
                                    id="editar_nome"
                                    name="nome"
                                    class="form-control"
                                    placeholder="Digite o nome completo"
                                    maxlength="45"
                                    autocomplete="off"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="editar_email" class="form-label">Email</label>
                                <input type="email"
                                    id="editar_email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Digite o e-mail"
                                    maxlength="45"
                                    autocomplete="off"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="editar_nova_senha" class="form-label">Nova senha</label>
                                <input type="password"
                                    id="editar_nova_senha"
                                    name="nova_senha"
                                    class="form-control"
                                    placeholder="Digite uma nova senha"
                                    autocomplete="new-password">
                                <small class="text-muted">
                                    Deixe em branco para manter a senha atual.
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editar_id_cargo" class="form-label">Cargo</label>
                                <select id="editar_id_cargo" name="id_cargo" class="form-select" required>
                                    <option value="" disabled>
                                        Selecione o cargo
                                    </option>
                                    <?php foreach ($cargos as $cargo) { ?>
                                        <option value="<?= $cargo["id_cargo"] ?>">
                                            <?= htmlspecialchars($cargo["nome_cargo"]) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editar_telefone" class="form-label">Telefone</label>
                                <input type="tel"
                                    id="editar_telefone"
                                    name="telefone"
                                    class="form-control"
                                    placeholder="+55 (47) 99999-9999"
                                    autocomplete="off"
                                    maxlength="19"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="editar_cep" class="form-label">CEP</label>
                                <div class="input-group">
                                    <input type="text"
                                        id="editar_cep"
                                        name="cep"
                                        class="form-control"
                                        placeholder="00000-000"
                                        inputmode="numeric"
                                        autocomplete="postal-code"
                                        maxlength="9"
                                        pattern="\d{5}-?\d{3}"
                                        aria-describedby="editar_cep-ajuda editar_cep-mensagem">
                                    <button type="button" class="btn btn-outline-secondary" id="editar_consultar-cep">
                                        Buscar CEP
                                    </button>
                                </div>
                                <small id="editar_cep-ajuda" class="text-muted">
                                    Informe o CEP para preencher o endereço; os campos podem ser editados.
                                </small>
                                <div id="editar_cep-mensagem" class="small mt-1" role="status" aria-live="polite"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editar_logradouro" class="form-label">Logradouro</label>
                            <input type="text"
                                id="editar_logradouro"
                                name="logradouro"
                                class="form-control"
                                maxlength="150"
                                autocomplete="address-line1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editar_bairro" class="form-label">Bairro</label>
                            <input type="text"
                                id="editar_bairro"
                                name="bairro"
                                class="form-control"
                                maxlength="100">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editar_cidade" class="form-label">Cidade</label>
                            <input type="text"
                                id="editar_cidade"
                                name="cidade"
                                class="form-control"
                                maxlength="100"
                                autocomplete="address-level2">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editar_uf" class="form-label">UF</label>
                            <input type="text"
                                id="editar_uf"
                                name="uf"
                                class="form-control"
                                maxlength="2"
                                autocomplete="address-level1"
                                pattern="[A-Za-z]{2}">
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