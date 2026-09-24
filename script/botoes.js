function abrirAviso(id) {
    document.getElementById("btnConfirmarExclusao").href = "excluir-user.php?id=" + id;
    let modal = new bootstrap.Modal(document.getElementById("modalExclusao"));
    modal.show();
}

function cancelarFormulario() {
    form?.reset();
    redirecionar("tela-geral-home.html");
}

function sairDoSistema(event) {
    event.preventDefault();

    const confirmarSaida = confirm(
        "Admin João, você realmente deseja sair do sistema?"
    );

    if (confirmarSaida) {
        redirecionar("index.html");
    }
}

function salvarSistema(event) {
    event.preventDefault();

    form?.reset();
    redirecionar("index.html");
}

botaoCancelar?.addEventListener("click", cancelarFormulario);
botaoSair?.addEventListener("click", sairDoSistema);
botaoSalvar?.addEventListener("click", salvarSistema);