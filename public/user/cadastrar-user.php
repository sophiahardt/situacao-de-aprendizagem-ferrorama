<?php
if (!defined("USUARIO_MODAIS")) {
    header("Location: visualizar-user.php");
    exit;
}
?>
<!-- MODAL DE CADASTRO -->
<div class="modal fade" id="modalCadastroUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="person-add-outline"></ion-icon>
                    Cadastrar novo usuário
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="visualizar-user.php" autocomplete="off" class="d-flex flex-column overflow-hidden">
                <input type="hidden" name="acao" value="cadastrar">
                <div class="modal-body">
                    <p class="text-muted">
                        Preencha os dados abaixo para cadastrar um novo usuário.
                    </p>
                    <?php if ($mensagem != "" && $abrir_modal) { ?>
                        <div class="alert alert-<?= $tipo_mensagem ?>" role="alert">
                            <?= htmlspecialchars($mensagem) ?>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text"
                                    id="nome"
                                    name="nome"
                                    class="form-control"
                                    placeholder="Digite o nome completo"
                                    maxlength="45"
                                    value="<?= htmlspecialchars($nome_form) ?>"
                                    autocomplete="off"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email"
                                    id="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Digite o e-mail"
                                    maxlength="45"
                                    value="<?= htmlspecialchars($email_form) ?>"
                                    autocomplete="off"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password"
                                    id="senha"
                                    name="senha"
                                    class="form-control"
                                    placeholder="Digite a senha"
                                    autocomplete="new-password"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_cargo" class="form-label">Cargo</label>
                                <select id="id_cargo" name="id_cargo" class="form-select" required>
                                    <option value="" disabled <?= $id_cargo_form == "" ? "selected" : "" ?>>
                                        Selecione o cargo
                                    </option>
                                    <?php foreach ($cargos as $cargo) { ?>
                                        <option value="<?= $cargo["id_cargo"] ?>"
                                            <?= $id_cargo_form == $cargo["id_cargo"] ? "selected" : "" ?>>
                                            <?= htmlspecialchars($cargo["nome_cargo"]) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="tel"
                                    id="telefone"
                                    name="telefone"
                                    class="form-control"
                                    placeholder="+55 (47) 99999-9999"
                                    value="<?= htmlspecialchars($telefone_form) ?>"
                                    autocomplete="off"
                                    maxlength="19"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="cep" class="form-label">CEP</label>
                                <div class="input-group">
                                    <input type="text"
                                        id="cep"
                                        name="cep"
                                        class="form-control"
                                        placeholder="00000-000"
                                        value="<?= htmlspecialchars($cep_form) ?>"
                                        inputmode="numeric"
                                        autocomplete="postal-code"
                                        maxlength="9"
                                        pattern="\d{5}-?\d{3}"
                                        aria-describedby="cep-ajuda cep-mensagem">
                                    <button type="button" class="btn btn-outline-secondary" id="consultar-cep">
                                        Buscar CEP
                                    </button>
                                </div>
                                <small id="cep-ajuda" class="text-muted">
                                    Informe o CEP para preencher o endereço; os campos podem ser editados.
                                </small>
                                <div id="cep-mensagem" class="small mt-1" role="status" aria-live="polite"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="logradouro" class="form-label">Logradouro</label>
                            <input type="text"
                                id="logradouro"
                                name="logradouro"
                                class="form-control"
                                maxlength="150"
                                value="<?= htmlspecialchars($logradouro_form) ?>"
                                autocomplete="address-line1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text"
                                id="bairro"
                                name="bairro"
                                class="form-control"
                                maxlength="100"
                                value="<?= htmlspecialchars($bairro_form) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text"
                                id="cidade"
                                name="cidade"
                                class="form-control"
                                maxlength="100"
                                value="<?= htmlspecialchars($cidade_form) ?>"
                                autocomplete="address-level2">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="uf" class="form-label">UF</label>
                            <input type="text"
                                id="uf"
                                name="uf"
                                class="form-control"
                                maxlength="2"
                                value="<?= htmlspecialchars($uf_form) ?>"
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
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>