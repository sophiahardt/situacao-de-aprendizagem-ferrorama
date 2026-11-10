// Scripts das telas de listagem com modais (sensores, rotas, trens, usuários e relatórios).

// ---------- Modal de exclusão (todas as listagens) ----------
function abrirAviso(id, arquivo) {
    document.getElementById("btnConfirmarExclusao").href = arquivo + "?id=" + id;

    let modal = new bootstrap.Modal(
        document.getElementById("modalExclusao")
    );

    modal.show();
}

// ---------- Preenche um modal de edição com os dados do botão "Editar" ----------
function preencherAoAbrir(idModal, preencher) {
    let modal = document.getElementById(idModal);

    if (!modal) {
        return;
    }

    modal.addEventListener("show.bs.modal", function(event) {
        let botao = event.relatedTarget;

        if (!botao) {
            return;
        }

        preencher(botao.dataset);
    });
}

// ---------- Sensores: alterna entre rota e trem ----------
function alternarLocalizacao(radioRota, radioTrem, campoRota, campoTrem, selectRota, selectTrem) {
    if (!radioRota || !radioTrem) {
        return;
    }

    if (radioRota.checked) {
        campoRota.style.display = "block";
        campoTrem.style.display = "none";
        selectRota.disabled = false;
        selectRota.required = true;
        selectTrem.disabled = true;
        selectTrem.required = false;
        selectTrem.value = "";
    } else if (radioTrem.checked) {
        campoRota.style.display = "none";
        campoTrem.style.display = "block";
        selectTrem.disabled = false;
        selectTrem.required = true;
        selectRota.disabled = true;
        selectRota.required = false;
        selectRota.value = "";
    }
}

function verificarLocalizacao() {
    alternarLocalizacao(
        document.getElementById("rota"),
        document.getElementById("trem"),
        document.getElementById("campo_rota"),
        document.getElementById("campo_trem"),
        document.getElementById("id_rota"),
        document.getElementById("id_trem")
    );
}

function verificarLocalizacaoEdicao() {
    alternarLocalizacao(
        document.getElementById("editar_rota"),
        document.getElementById("editar_trem"),
        document.getElementById("campo_rota_edicao"),
        document.getElementById("campo_trem_edicao"),
        document.getElementById("editar_id_rota"),
        document.getElementById("editar_id_trem")
    );
}

document.addEventListener("DOMContentLoaded", function() {
    // Sensores
    if (document.getElementById("modalCadastroSensor")) {
        verificarLocalizacao();
    }

    preencherAoAbrir("modalEditarSensor", function(dados) {
        document.getElementById("editar_id_sensor").value = dados.id;
        document.getElementById("editar_nome_sensor").value = dados.nome;
        document.getElementById("editar_tipo_sensor").value = dados.tipo;

        if (dados.localizacao == "rota") {
            document.getElementById("editar_rota").checked = true;
            verificarLocalizacaoEdicao();
            document.getElementById("editar_id_rota").value = dados.rota;
        } else {
            document.getElementById("editar_trem").checked = true;
            verificarLocalizacaoEdicao();
            document.getElementById("editar_id_trem").value = dados.trem;
        }
    });

    // Rotas
    preencherAoAbrir("modalEditarRota", function(dados) {
        document.getElementById("editar_id_rota").value = dados.id;
        document.getElementById("editar_nome_rota").value = dados.nome;
        document.getElementById("editar_descricao").value = dados.descricao;
        document.getElementById("editar_extensao").value = dados.extensao;
        document.getElementById("editar_tempo_estimado_minutos").value = dados.tempo;
    });

    // Trens
    preencherAoAbrir("modalEditarTrem", function(dados) {
        document.getElementById("editar_id_trem").value = dados.id;
        document.getElementById("editar_nome_trem").value = dados.nome;
        document.getElementById("editar_velocidade_maxima").value = dados.velocidade;
        document.getElementById("editar_tipo_trem").value = dados.tipo;
        document.getElementById("editar_id_rota").value = dados.rota;
        document.getElementById("editar_id_usuario").value = dados.usuario;
    });
});