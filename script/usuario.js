
// ---------- Consulta de CEP ----------
(() => {
    const cepInput = document.querySelector("#cep");
    const consultarButton = document.querySelector("#consultar-cep");
    const mensagem = document.querySelector("#cep-mensagem");


    if (!(cepInput instanceof HTMLInputElement)
        || !(consultarButton instanceof HTMLButtonElement)
        || !(mensagem instanceof HTMLElement)) {
        return;
    }


    const campos = {
        logradouro: document.querySelector("#logradouro"),
        bairro: document.querySelector("#bairro"),
        cidade: document.querySelector("#cidade"),
        uf: document.querySelector("#uf")
    };
    let digitsAtuais = cepInput.value.replace(/\D/g, "");
    let ultimoCepConsultado = "";
    let controlador;
    let numeroRequisicao = 0;
    let temporizador;


    const mostrarMensagem = (texto, tipo = "muted") => {
        mensagem.textContent = texto;
        mensagem.className = `small mt-1 text-${tipo}`;
    };


    const cancelarConsulta = () => {
        numeroRequisicao += 1;
        if (controlador) {
            controlador.abort();
            controlador = undefined;
        }
    };


    const consultarCep = async () => {
        const cep = cepInput.value.replace(/\D/g, "");
        if (!/^\d{8}$/.test(cep)) {
            mostrarMensagem("Informe um CEP com 8 dígitos.", "danger");
            return;
        }
        if (cep === ultimoCepConsultado) {
            return;
        }


        cancelarConsulta();
        const requisicaoAtual = numeroRequisicao;
        const controladorAtual = new AbortController();
        controlador = controladorAtual;
        const timeout = window.setTimeout(() => controladorAtual.abort(), 10000);
        consultarButton.disabled = true;
        mostrarMensagem("Consultando CEP...", "secondary");


        try {
            const resposta = await fetch(`https://viacep.com.br/ws/${cep}/json/`, {
                headers: { Accept: "application/json" },
                signal: controladorAtual.signal
            });
            if (!resposta.ok) {
                throw new Error("Falha na comunicação com o serviço de CEP.");
            }


            let dados;
            try {
                dados = await resposta.json();
            } catch {
                throw new Error("O serviço de CEP retornou uma resposta inválida.");
            }
            if (dados.erro) {
                ultimoCepConsultado = cep;
                mostrarMensagem("CEP não encontrado. Confira o número ou preencha o endereço manualmente.", "warning");
                return;
            }
            if (!dados.cep || !dados.localidade || !dados.uf) {
                throw new Error("A resposta do serviço de CEP está incompleta.");
            }


            ultimoCepConsultado = cep;
            cepInput.value = dados.cep;
            campos.logradouro.value = dados.logradouro || "";
            campos.bairro.value = dados.bairro || "";
            campos.cidade.value = dados.localidade;
            campos.uf.value = dados.uf;
            mostrarMensagem("Endereço preenchido. Você ainda pode editar os campos.", "success");
        } catch (erro) {
            if (requisicaoAtual !== numeroRequisicao) {
                return;
            }
            ultimoCepConsultado = "";
            if (erro.name === "AbortError") {
                mostrarMensagem("A consulta demorou demais. Verifique sua conexão e tente novamente.", "danger");
            } else if (erro instanceof TypeError) {
                mostrarMensagem("Falha na comunicação com o serviço de CEP. Verifique sua conexão e tente novamente.", "danger");
            } else {
                mostrarMensagem(erro.message || "Não foi possível consultar o CEP. Tente novamente ou preencha o endereço manualmente.", "danger");
            }
        } finally {
            window.clearTimeout(timeout);
            if (requisicaoAtual === numeroRequisicao) {
                consultarButton.disabled = false;
                controlador = undefined;
            }
        }
    };


    cepInput.value = digitsAtuais.length > 5
        ? `${digitsAtuais.slice(0, 5)}-${digitsAtuais.slice(5, 8)}`
        : digitsAtuais.slice(0, 8);


    cepInput.addEventListener("input", () => {
        const digits = cepInput.value.replace(/\D/g, "").slice(0, 8);
        cepInput.value = digits.length > 5
            ? `${digits.slice(0, 5)}-${digits.slice(5)}`
            : digits;


        if (digits !== digitsAtuais) {
            digitsAtuais = digits;
            ultimoCepConsultado = "";
            cancelarConsulta();
            consultarButton.disabled = false;
        }


        window.clearTimeout(temporizador);
        if (digits.length === 8) {
            temporizador = window.setTimeout(consultarCep, 350);
        } else {
            mostrarMensagem("");
        }
    });


    consultarButton.addEventListener("click", consultarCep);
})();

// ---------- Máscara de telefone brasileiro ----------
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

document.addEventListener("DOMContentLoaded", function() {
    const telefone = document.getElementById("telefone");

    if (telefone) {
        telefone.addEventListener("input", function() {
            this.value = formatarTelefone(this.value);
        });
    }
});