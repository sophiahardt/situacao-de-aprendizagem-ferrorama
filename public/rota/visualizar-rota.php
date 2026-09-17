<?php

require_once "../../infra/conexao.php";

$sql = "SELECT id_rota, nome_rota, extensao_km, tempo_estimado_min
        FROM rota
        ORDER BY id_rota ASC";

$resultado = $conexao->query($sql);

$rotas = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];

?>