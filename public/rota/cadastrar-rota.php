<?php

require_once "../../infra/conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome_rota = $_POST["nome_rota"] ?? "";
    $extensao_km = $_POST["extensao_km"] ?? "";
    $tempo_estimado_min = $_POST["tempo_estimado_min"] ?? "";

    if ($nome_rota == "" || $extensao_km == "" || $tempo_estimado_min == "") {

        $mensagem = "Preencha todos os campos.";

    } else {

        $sql = "INSERT INTO rota
                (id_rota, nome_rota, extensao_km, tempo_estimado_min)
                VALUES (NULL, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            "sss",
            $nome_rota,
            $extensao_km,
            $tempo_estimado_min
        );