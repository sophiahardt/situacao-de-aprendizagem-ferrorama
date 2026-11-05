
const adminEmail = "admin@gmail.com";
const adminSenha = "12344321";

function validarLogin(email, senha) {

    if (!email || !senha) {
        return "Preencha todos os campos.";
    }

    if (!email.endsWith("@gmail.com")) {
        return "Informe um email válido.";
    }

    if (senha.length < 8) {
        return "A senha precisa ter no mínimo 8 caracteres.";
    }

    if (email !== adminEmail || senha !== adminSenha) {
        return "Email ou senha incorretos.";
    }

    return "sucesso";
}

function validarFormulario(nome, cargo, email, telefone) {

    if (
        nome === "" ||
        cargo === "Selecione o cargo" ||
        email === "" ||
        telefone === ""
    ) {
        alert("Preencha todos os campos!");
        return false;
    }

    return true;
}

function alterarLocalizacao() {

    let rota = document.getElementById("rota");
    let localizacao = document.getElementById("id_localizacao");

    let opcoes = localizacao.querySelectorAll("option");

    if (rota.checked) {

        localizacao.innerHTML = `
            <option selected disabled>
                Selecione a rota vinculada ao sensor
            </option>
        `;

        opcoes.forEach(function(opcao) {

            if (opcao.dataset.tipo === "rota") {
                localizacao.appendChild(opcao);
            }

        });

    } else {

        localizacao.innerHTML = `
            <option selected disabled>
                Selecione o trem vinculado ao sensor
            </option>
        `;

        opcoes.forEach(function(opcao) {

            if (opcao.dataset.tipo === "trem") {
                localizacao.appendChild(opcao);
            }

        });

    }
}

function verificarLocalizacao() {

    let rota = document.getElementById("rota");
    let trem = document.getElementById("trem");
    let campoRota = document.getElementById("campo_rota");
    let campoTrem = document.getElementById("campo_trem");
    let selectRota = document.getElementById("id_rota");
    let selectTrem = document.getElementById("id_trem");
    let labelRota = document.querySelector('label[for="rota"]');
    let labelTrem = document.querySelector('label[for="trem"]');

    if (rota.checked) {

        campoRota.style.display = "block";
        campoTrem.style.display = "none";

        selectRota.name = "id_localizacao";
        selectTrem.name = "";

        selectRota.required = true;
        selectTrem.required = false;
        selectTrem.value = "";

        labelRota.classList.remove("btn-outline-dark");
        labelRota.classList.add("btn-primary");

        labelTrem.classList.remove("btn-primary");
        labelTrem.classList.add("btn-outline-dark");

    } else if (trem.checked) {

        campoRota.style.display = "none";
        campoTrem.style.display = "block";

        selectRota.name = "";
        selectTrem.name = "id_localizacao";

        selectRota.required = false;
        selectTrem.required = true;
        selectRota.value = "";

        labelTrem.classList.remove("btn-outline-dark");
        labelTrem.classList.add("btn-primary");

        labelRota.classList.remove("btn-primary");
        labelRota.classList.add("btn-outline-dark");

    }
}

// Máscara de telefone brasileiro
function formatarTelefone(telefone) {

    const digits = telefone.replace(/\D/g, "").slice(0, 13);

    if (!digits) {
        return "";
    }

    let numero = digits;

    if (numero.startsWith("55")) {
        numero = numero.slice(2);
    }

    if (numero.length <= 2) {
        return `+55 (${numero}`;
    }

    const ddd = numero.slice(0, 2);
    const restante = numero.slice(2);

    if (restante.length <= 5) {
        return `+55 (${ddd}) ${restante}`;
    }

    if (restante.length <= 9) {
        return `+55 (${ddd}) ${restante.slice(0, 5)}-${restante.slice(5)}`;
    }

    return `+55 (${ddd}) ${restante.slice(0, 5)}-${restante.slice(5, 9)}`;
}

// Aplicar máscara automaticamente
document.addEventListener("DOMContentLoaded", function() {

    const telefone = document.getElementById("telefone");

    if (telefone) {

        telefone.addEventListener("input", function() {
            this.value = formatarTelefone(this.value);
        });

    }

});
