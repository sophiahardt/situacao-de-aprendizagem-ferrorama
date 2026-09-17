<?php

require_once "../../infra/conexao.php";

$sql = "SELECT id_rota, nome_rota, extensao_km, tempo_estimado_min
        FROM rota
        ORDER BY id_rota ASC";

$resultado = $conexao->query($sql);

$rotas = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rotas Cadastradas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script type="module"
        src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js">
    </script>

    <script nomodule
        src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js">
    </script>

    <link rel="stylesheet" href="../../style/style.css">

</head>