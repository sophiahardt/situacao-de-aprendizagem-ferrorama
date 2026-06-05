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

    if(rota.checked) {
        localizacao.innerHTML = `<option selected disabled> Selecione a rota vinculada ao sensor </option>`;
/*Futuramente: carregar as rotas cadastradas no banco de dados.

Exemplo:
localizacao.innerHTML = `
<option value="1">Rota Norte</option>
<option value="2">Rota Sul</option>`;*/
    }else{
        localizacao.innerHTML = `<option selected disabled> Selecione o trem vinculado ao sensor </option>`;
/*Futuramente:carregar os trens cadastrados no banco de dados.*/
    }
}