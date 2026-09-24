<?php
session_start();

require_once "../../infra/conexao.php";

$mensagem = "";

$id_rota = $_GET["id"] ?? "";

if ($id_rota == "") {
    header("Location: visualizar-rota.php");
    exit;
}

$sql_busca = "SELECT * FROM rota WHERE id_rota = ?";
$stmt_busca = $conexao->prepare($sql_busca);
$stmt_busca->bind_param("i", $id_rota);
$stmt_busca->execute();
$resultado = $stmt_busca->get_result();
$rota = $resultado->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_rota = $_POST["nome_rota"] ?? "";
    $extensao = $_POST["extensao"] ?? "";
    $tempo_estimado = $_POST["tempo_estimado"] ?? "";

    if ($nome_rota == "" || $extensao == "" || $tempo_estimado == "") {
        $mensagem = "Preencha todos os campos.";
    } else {
        $sql = "UPDATE rota SET nome_rota = ?, extensao = ?, tempo_estimado = ? WHERE id_rota = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("siii", $nome_rota, $extensao, $tempo_estimado, $id_rota);

        if ($stmt->execute()) {
            header("Location: visualizar-rota.php");
            exit;
        } else {
            $mensagem = "Erro ao editar a rota.";
        }
    }
}
?>
