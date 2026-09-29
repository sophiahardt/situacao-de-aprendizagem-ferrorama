<?php

require_once "../infra/protecao.php";

verificarAdministrador();

require_once "../infra/conexao.php";

$trens_ativos = 12;
$sensores_ativos = 34;
$sensores_inativos = 4;
$alertas_hoje = 15;

$rotas_ativas = 0;
$resultado = $conexao->query("SELECT COUNT(*) AS total FROM rota");

if ($resultado) {
    $linha = $resultado->fetch_assoc();
    $rotas_ativas = $linha["total"];
}