<?php
if (!defined("USUARIO_MODAIS")) {
    require_once "../../infra/conexao.php";
    require_once "../../infra/protecao.php";
    verificarAdministrador();

    $id_usuario = $_GET["id"] ?? "";

    if ($id_usuario == "" || !is_numeric($id_usuario) || $id_usuario <= 0) {
        header("Location: visualizar-user.php");
        exit;
    }

    $id_usuario = (int) $id_usuario;

    $sql_admin = "SELECT id_cargo FROM usuario WHERE id_usuario = ?";
    $stmt_admin = $conexao->prepare($sql_admin);
    $stmt_admin->bind_param("i", $id_usuario);
    $stmt_admin->execute();

    $resultado_admin = $stmt_admin->get_result();
    $usuario_excluir = $resultado_admin->fetch_assoc();

    if (!$usuario_excluir) {
        header("Location: visualizar-user.php?erro=exclusao");
        exit;
    }

    if ($usuario_excluir["id_cargo"] == 1) {

        $sql_contagem = "SELECT COUNT(*) AS total FROM usuario WHERE id_cargo = 1";
        $resultado_contagem = $conexao->query($sql_contagem);
        $contagem = $resultado_contagem->fetch_assoc();

        if ($contagem["total"] <= 1) {
            header("Location: visualizar-user.php?erro=ultimo_admin");
            exit;
        }
    }

    $sql_trem = "SELECT id_trem FROM trem WHERE id_usuario = ?";
    $stmt_trem = $conexao->prepare($sql_trem);
    $stmt_trem->bind_param("i", $id_usuario);
    $stmt_trem->execute();

    $resultado = $stmt_trem->get_result();

    if ($resultado->num_rows > 0) {
        header("Location: visualizar-user.php?erro=trens");
        exit;
    }

    $sql = "DELETE FROM usuario WHERE id_usuario = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_usuario);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        header("Location: visualizar-user.php?sucesso=1");
        exit;
    } else {
        header("Location: visualizar-user.php?erro=exclusao");
        exit;
    }
}
?>
<div class="modal" id="modalExclusao" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body p-4">
                <div style="font-size: 40px;">⚠️</div>
                <h4 class="fw-bold mt-2">
                    Deseja continuar?
                </h4>
                <p class="fw-bold mb-4">
                    Após a confirmação, não será possível reverter esta ação.
                </p>
                <div class="d-flex justify-content-center gap-5">
                    <button type="button"
                        class="btn btn-secondary btn-cancelar px-5"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <a id="btnConfirmarExclusao"
                        href="#"
                        class="btn btn-danger px-5">
                        Excluir
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>