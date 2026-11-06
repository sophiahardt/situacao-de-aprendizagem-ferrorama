function verificarLocalizacao() {
    let rota = document.getElementById("rota");
    let trem = document.getElementById("trem");

    let campoRota = document.getElementById("campo_rota");
    let campoTrem = document.getElementById("campo_trem");

    let selectRota = document.getElementById("id_rota");
    let selectTrem = document.getElementById("id_trem");

    if (rota.checked) {
        campoRota.style.display = "block";
        campoTrem.style.display = "none";
        selectRota.disabled = false;
        selectRota.required = true;
        selectTrem.disabled = true;
        selectTrem.required = false;
        selectTrem.value = "";
    } else if (trem.checked) {
        campoRota.style.display = "none";
        campoTrem.style.display = "block";
        selectTrem.disabled = false;
        selectTrem.required = true;
        selectRota.disabled = true;
        selectRota.required = false;
        selectRota.value = "";
    }
}

function verificarLocalizacaoEdicao() {
    let rota = document.getElementById("editar_rota");
    let trem = document.getElementById("editar_trem");

    let campoRota = document.getElementById("campo_rota_edicao");
    let campoTrem = document.getElementById("campo_trem_edicao");

    let selectRota = document.getElementById("editar_id_rota");
    let selectTrem = document.getElementById("editar_id_trem");

    if (rota.checked) {
        campoRota.style.display = "block";
        campoTrem.style.display = "none";
        selectRota.disabled = false;
        selectRota.required = true;
        selectTrem.disabled = true;
        selectTrem.required = false;
        selectTrem.value = "";
    } else if (trem.checked) {
        campoRota.style.display = "none";
        campoTrem.style.display = "block";
        selectTrem.disabled = false;
        selectTrem.required = true;
        selectRota.disabled = true;
        selectRota.required = false;
        selectRota.value = "";
    }
}

document.addEventListener("DOMContentLoaded", function() {
    verificarLocalizacao();

    let modalEdicao = document.getElementById("modalEditarSensor");

    if (modalEdicao) {
        modalEdicao.addEventListener("show.bs.modal", function(event) {
            let botao = event.relatedTarget;

            if (!botao) {
                return;
            }

            document.getElementById("editar_id_sensor").value = botao.dataset.id;
            document.getElementById("editar_nome_sensor").value = botao.dataset.nome;
            document.getElementById("editar_tipo_sensor").value = botao.dataset.tipo;

            if (botao.dataset.localizacao == "rota") {
                document.getElementById("editar_rota").checked = true;
                verificarLocalizacaoEdicao();
                document.getElementById("editar_id_rota").value = botao.dataset.rota;
            } else {
                document.getElementById("editar_trem").checked = true;
                verificarLocalizacaoEdicao();
                document.getElementById("editar_id_trem").value = botao.dataset.trem;
            }
        });
    }
});
