<?php
// Verifica se o arquivo está sendo acessado pelo sistema de modais

if (!defined("ROTA_MODAIS")) {

     // Redireciona para a página de rotas caso o acesso seja direto

    header("Location: visualizar-rota.php");
    exit;
}
?>

<!-- Modal de edição de rota -->

<div class="modal fade" id="modalEditarRota" tabindex="-1">

  <!-- Define o tamanho e a posição do modal -->

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

        <!-- Cabeçalho do modal -->

            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="pencil-outline"></ion-icon>
                    Editar rota
                </h5>

            </div>

               <!-- Formulário que envia os dados para a página de rotas -->

            <form method="POST" action="visualizar-rota.php">

             <!-- Informa qual ação será realizada -->

                <input type="hidden" name="acao" value="editar">

                   <!-- Guarda o ID da rota que será editada -->

                <input type="hidden" name="id_rota" id="editar_id_rota">

                    <!-- Corpo do modal -->

                <div class="modal-body">

                 <!-- Texto de orientação -->

                    <p class="text-muted">
                        Altere as informações da rota selecionada.
                    </p>
                    
                     <!-- Exibe uma mensagem de sucesso ou erro, quando necessário -->

                    <?php if ($mensagem != "" && $abrir_modal_edicao) { ?>
                        <div class="alert alert-<?= $tipo_mensagem ?>" role="alert">
                            <?= htmlspecialchars($mensagem) ?>
                        </div>
                    <?php } ?>

                     <!-- Organiza os campos do formulário em duas colunas -->

                    <div class="row">

                       <!-- Primeira coluna: informações principais -->

                        <div class="col-md-6">

                        <!-- Campo para informar o nome da rota -->

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

                               <!-- Campo para informar a extensão da rota em quilômetros -->

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

                               <!-- Campo para informar o tempo estimado da rota em minutos -->

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

                               <!-- Campo para selecionar o trem que utilizará a rota -->

                            <div class="mb-3">
                                <label for="editar_id_trem" class="form-label">Trem</label>
                                <select name="id_trem" id="editar_id_trem" class="form-select" required>

                                  <!-- Opção inicial para orientar a seleção -->

                                    <option value="" disabled>Selecione o trem que anda nesta rota</option>

                                     <!-- Percorre a lista de trens cadastrados -->

                                    <?php foreach ($trens as $trem) { ?>

                                      <!-- Cria uma opção para cada trem -->

                                        <option value="<?= $trem["id_trem"] ?>">

                                        <!-- Exibe o nome do trem -->

                                            <?= htmlspecialchars($trem["nome_trem"]) ?>

                                             <!-- Informa em qual rota o trem está ou se está sem rota -->

                                            <?= $trem["nome_rota"] ? "(hoje na rota " . htmlspecialchars($trem["nome_rota"]) . ")" : "(sem rota)" ?>
                                        </option>
                                    <?php } ?>
                                </select>

                                 <!-- Explica o que acontece ao trocar o trem da rota -->

                                <div class="form-text">
                                    Ao trocar o trem, o anterior fica sem rota. Se o trem escolhido
                                    estiver em outra rota, ele passa para esta.
                                </div>
                            </div>
                        </div>

                          <!-- Segunda coluna: descrição da rota -->

                        <div class="col-md-6">

                         <!-- Campo para descrever os detalhes da rota -->

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

                   <!-- Rodapé do modal com os botões -->

                <div class="modal-footer">

                   <!-- Botão para cancelar e fechar o modal -->

                    <button type="button" class="btn btn-light btn-cancelar" data-bs-dismiss="modal">
                        <ion-icon name="close-outline"></ion-icon>
                        Cancelar

                    <!-- Botão para enviar o formulário e salvar as alterações -->

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