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

$total_sensores = $sensores_ativos + $sensores_inativos;
$porcentagem_ativos = ($sensores_ativos / $total_sensores) * 100;
$porcentagem_inativos = 100 - $porcentagem_ativos;

$velocidades = [
    ["trem" => "Trem Alpha", "valor" => 88],
    ["trem" => "Trem Beta", "valor" => 75],
    ["trem" => "Trem Gama", "valor" => 60],
    ["trem" => "Trem Delta", "valor" => 67]
];

$alertas = [
    ["hora" => "10:30", "localizacao" => "Trem Alpha", "tipo" => "Excesso de velocidade"],
    ["hora" => "10:12", "localizacao" => "Trem Beta", "tipo" => "Sensor offline"],
    ["hora" => "9:45", "localizacao" => "Linha verde", "tipo" => "Presença detectada"]
];
?>