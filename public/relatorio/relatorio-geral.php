<?php
require_once "../../infra/protecao.php";
verificarLogin();
require_once "../../infra/conexao.php";

$eh_admin = ehAdministrador();

function contar($sql)
{
    global $conexao;

    try {
        $resultado = $conexao->query($sql);
        $linha = $resultado ? $resultado->fetch_row() : null;

        return (int) ($linha[0] ?? 0);
    } catch (Throwable $erro) {
        return 0;
    }
}

function listar($sql)
{
    global $conexao;

    try {
        $resultado = $conexao->query($sql);

        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    } catch (Throwable $erro) {
        return [];
    }
}

function numeroBr($valor)
{
    $texto = number_format((float) $valor, 2, ",", ".");

    return rtrim(rtrim($texto, "0"), ",");
}


$totais = [
    "usuarios" => contar("SELECT COUNT(*) FROM usuario"),
    "trens" => contar("SELECT COUNT(*) FROM trem"),
    "trens_sem_rota" => contar("SELECT COUNT(*) FROM trem WHERE id_rota IS NULL"),
    "rotas" => contar("SELECT COUNT(*) FROM rota"),
    "sensores" => contar("SELECT COUNT(*) FROM sensor"),
    "leituras" => contar("SELECT COUNT(*) FROM leitura_sensor")
];

$trens = listar("SELECT trem.nome_trem, trem.tipo_trem, trem.velocidade_maxima,
                        usuario.nome AS responsavel, rota.nome_rota,
                        (SELECT COUNT(*) FROM sensor WHERE sensor.id_trem = trem.id_trem) AS sensores
                 FROM trem
                 LEFT JOIN usuario ON trem.id_usuario = usuario.id_usuario
                 LEFT JOIN rota ON trem.id_rota = rota.id_rota
                 ORDER BY trem.nome_trem");

$rotas = listar("SELECT rota.nome_rota, rota.extensao, rota.tempo_estimado_minutos, rota.data_criacao,
                        GROUP_CONCAT(trem.nome_trem ORDER BY trem.nome_trem SEPARATOR ', ') AS trens
                 FROM rota
                 LEFT JOIN trem ON trem.id_rota = rota.id_rota
                 GROUP BY rota.id_rota
                 ORDER BY rota.nome_rota");

$sensores = listar("SELECT sensor.id_sensor, sensor.nome_sensor, sensor.tipo_sensor, sensor.localizacao,
                           trem.nome_trem, rota.nome_rota
                    FROM sensor
                    LEFT JOIN trem ON sensor.id_trem = trem.id_trem
                    LEFT JOIN rota ON sensor.id_rota = rota.id_rota
                    ORDER BY sensor.id_sensor");

$leituras = listar("SELECT leitura_sensor.valor_leitura, leitura_sensor.unidade_medida, leitura_sensor.dt_hora,
                           sensor.nome_sensor, sensor.tipo_sensor, sensor.localizacao,
                           trem.nome_trem, rota.nome_rota
                    FROM leitura_sensor
                    INNER JOIN sensor ON leitura_sensor.id_sensor = sensor.id_sensor
                    LEFT JOIN trem ON sensor.id_trem = trem.id_trem
                    LEFT JOIN rota ON sensor.id_rota = rota.id_rota
                    ORDER BY leitura_sensor.dt_hora DESC
                    LIMIT 10");

$usuarios = $eh_admin
    ? listar("SELECT usuario.nome, usuario.email, usuario.telefone, cargo.nome_cargo
              FROM usuario
              INNER JOIN cargo ON usuario.id_cargo = cargo.id_cargo
              ORDER BY usuario.nome")
    : [];

$quadros = [];

if ($eh_admin) {
    $quadros[] = ["people-outline", "Usuários", $totais["usuarios"]];
}

$quadros[] = ["train-outline", "Trens", $totais["trens"]];
$quadros[] = ["git-branch-outline", "Trens sem rota", $totais["trens_sem_rota"]];
$quadros[] = ["map-outline", "Rotas", $totais["rotas"]];
$quadros[] = ["hardware-chip-outline", "Sensores", $totais["sensores"]];
$quadros[] = ["pulse-outline", "Leituras registradas", $totais["leituras"]];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório geral | Ferroramas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../../style/style.css?v=12">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-sistema">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../tela-geral-home.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../sensor/visualizar-sensor.php">Sensores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../trem/visualizar-trem.php">Trens</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../rota/visualizar-rota.php">Rotas</a>
                    </li>
                    <?php if (ehAdministrador()) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../user/visualizar-user.php">Usuários</a>
                        </li>
                    <?php } ?>

                    <li class="nav-item">
                        <a class="nav-link active" href="relatorio-geral.php">Relatórios</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3">
                        <span class="nav-link d-flex align-items-center gap-2">
                            <ion-icon name="person-circle-outline"></ion-icon>
                            <?= htmlspecialchars($_SESSION["nome_usuario"] ?? "Usuário") ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-outline-light btn-sm d-flex align-items-center gap-2"
                            data-bs-toggle="modal" data-bs-target="#modalLogout">
                            <ion-icon name="log-out-outline"></ion-icon>
                            <span>Sair</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid p-4 relatorio-geral">
        <header class="relatorio-cabecalho">
            <div>
                <span class="relatorio-selo-topo">
                    <ion-icon name="document-text-outline" aria-hidden="true"></ion-icon>
                    Ferroramas
                </span>
                <h1>Relatório geral do sistema</h1>
                <p>Resumo da operação: trens, rotas, sensores e últimas leituras registradas.</p>
            </div>
            <div class="relatorio-acoes no-print">
                <button type="button" class="btn btn-destaque" onclick="window.print()">
                    <ion-icon name="download-outline" aria-hidden="true"></ion-icon>
                    Salvar em PDF
                </button>
            </div>
        </header>

        <p class="relatorio-dica no-print">
            <ion-icon name="information-circle-outline" aria-hidden="true"></ion-icon>
            Ao clicar em <strong>Salvar em PDF,</strong> escolha <strong>"Salvar como PDF"</strong> no destino da impressão.
        </p>

        <!-- RESUMO -->
        <section class="relatorio-quadros" aria-label="Resumo">
            <?php foreach ($quadros as $quadro) { ?>
                <div class="relatorio-quadro">
                    <span class="relatorio-quadro-icone">
                        <ion-icon name="<?= $quadro[0] ?>" aria-hidden="true"></ion-icon>
                    </span>
                    <div>
                        <strong><?= $quadro[2] ?></strong>
                        <span><?= $quadro[1] ?></span>
                    </div>
                </div>
            <?php } ?>
        </section>

        <!-- TRENS -->
        <section class="relatorio-secao">
            <h2><ion-icon name="train-outline" aria-hidden="true"></ion-icon> Trens</h2>
            <div class="table-responsive">
                <table class="table relatorio-tabela">
                    <thead>
                        <tr>
                            <th>Trem</th>
                            <th>Tipo</th>
                            <th>Velocidade máxima</th>
                            <th>Responsável</th>
                            <th>Rota</th>
                            <th class="text-center">Sensores</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($trens as $trem) { ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($trem["nome_trem"]) ?></strong></td>
                                <td><?= htmlspecialchars($trem["tipo_trem"]) ?></td>
                                <td><?= numeroBr($trem["velocidade_maxima"]) ?> km/h</td>
                                <td><?= htmlspecialchars($trem["responsavel"] ?? "Não definido") ?></td>
                                <td>
                                    <?php if ($trem["nome_rota"] !== null) { ?>
                                        <span class="selo selo-ok">
                                            <ion-icon name="checkmark-circle-outline" aria-hidden="true"></ion-icon>
                                            <?= htmlspecialchars($trem["nome_rota"]) ?>
                                        </span>
                                    <?php } else { ?>
                                        <span class="selo selo-atencao">
                                            <ion-icon name="alert-circle-outline" aria-hidden="true"></ion-icon>
                                            Sem rota
                                        </span>
                                    <?php } ?>
                                </td>
                                <td class="text-center"><?= $trem["sensores"] ?></td>
                            </tr>
                        <?php } ?>
                        <?php if (count($trens) == 0) { ?>
                            <tr>
                                <td colspan="6" class="relatorio-vazio">Nenhum trem cadastrado.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ROTAS -->
        <section class="relatorio-secao">
            <h2><ion-icon name="map-outline" aria-hidden="true"></ion-icon> Rotas</h2>
            <div class="table-responsive">
                <table class="table relatorio-tabela">
                    <thead>
                        <tr>
                            <th>Rota</th>
                            <th>Extensão</th>
                            <th>Tempo estimado</th>
                            <th>Trens que andam nela</th>
                            <th>Criada em</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rotas as $rota) { ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($rota["nome_rota"]) ?></strong></td>
                                <td><?= numeroBr($rota["extensao"]) ?> km</td>
                                <td><?= (int) $rota["tempo_estimado_minutos"] ?> min</td>
                                <td><?= htmlspecialchars($rota["trens"] ?? "Nenhum trem") ?></td>
                                <td><?= date("d/m/Y", strtotime($rota["data_criacao"])) ?></td>
                            </tr>
                        <?php } ?>
                        <?php if (count($rotas) == 0) { ?>
                            <tr>
                                <td colspan="5" class="relatorio-vazio">Nenhuma rota cadastrada.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- SENSORES -->
        <section class="relatorio-secao">
            <h2><ion-icon name="hardware-chip-outline" aria-hidden="true"></ion-icon> Sensores</h2>
            <div class="table-responsive">
                <table class="table relatorio-tabela">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sensor</th>
                            <th>Tipo de dado</th>
                            <th>Instalado em</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sensores as $sensor) { ?>
                            <?php $em_trem = $sensor["localizacao"] != "rota"; ?>
                            <tr>
                                <td><?= $sensor["id_sensor"] ?></td>
                                <td><strong><?= htmlspecialchars($sensor["nome_sensor"]) ?></strong></td>
                                <td><?= htmlspecialchars($sensor["tipo_sensor"] ?? "") ?></td>
                                <td>
                                    <span class="selo selo-info">
                                        <ion-icon name="<?= $em_trem ? "train-outline" : "map-outline" ?>" aria-hidden="true"></ion-icon>
                                        <?= $em_trem ? "Trem" : "Rota" ?>:
                                        <?= htmlspecialchars(($em_trem ? $sensor["nome_trem"] : $sensor["nome_rota"]) ?? "não identificado") ?>
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if (count($sensores) == 0) { ?>
                            <tr>
                                <td colspan="4" class="relatorio-vazio">Nenhum sensor cadastrado.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ÚLTIMAS LEITURAS -->
        <section class="relatorio-secao">
            <h2><ion-icon name="pulse-outline" aria-hidden="true"></ion-icon> Últimas leituras dos sensores</h2>
            <div class="table-responsive">
                <table class="table relatorio-tabela">
                    <thead>
                        <tr>
                            <th>Data e hora</th>
                            <th>Sensor</th>
                            <th>Tipo de dado</th>
                            <th>Valor</th>
                            <th>Local</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leituras as $leitura) { ?>
                            <tr>
                                <td><?= date("d/m/Y H:i", strtotime($leitura["dt_hora"])) ?></td>
                                <td><strong><?= htmlspecialchars($leitura["nome_sensor"]) ?></strong></td>
                                <td><?= htmlspecialchars($leitura["tipo_sensor"] ?? "") ?></td>
                                <td><?= numeroBr($leitura["valor_leitura"]) ?> <?= htmlspecialchars($leitura["unidade_medida"] ?? "") ?></td>
                                <td><?= htmlspecialchars(($leitura["localizacao"] == "rota" ? $leitura["nome_rota"] : $leitura["nome_trem"]) ?? "—") ?></td>
                            </tr>
                        <?php } ?>
                        <?php if (count($leituras) == 0) { ?>
                            <tr>
                                <td colspan="5" class="relatorio-vazio">Nenhuma leitura registrada até o momento.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>

        <?php if ($eh_admin) { ?>
            <section class="relatorio-secao">
                <h2><ion-icon name="people-outline" aria-hidden="true"></ion-icon> Usuários</h2>
                <div class="table-responsive">
                    <table class="table relatorio-tabela">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Cargo</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario) { ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($usuario["nome"]) ?></strong></td>
                                    <td><?= htmlspecialchars($usuario["nome_cargo"]) ?></td>
                                    <td><?= htmlspecialchars($usuario["email"]) ?></td>
                                    <td><?= htmlspecialchars($usuario["telefone"] ?? "") ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php } ?>

        <footer class="relatorio-rodape">
            Ferroramas — Sistema de monitoramento ferroviário
        </footer>
    </main>

    <div class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="modalLogoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLogoutLabel">
                        <ion-icon name="log-out-outline"></ion-icon>
                        Confirmar logout
                    </h5>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Tem certeza que deseja sair do sistema?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-cancelar" data-bs-dismiss="modal">
                        <ion-icon name="close-outline"></ion-icon>
                        Cancelar
                    </button>
                    <a class="btn btn-danger btn-login px-4" href="../logout.php">
                        <ion-icon name="log-out-outline"></ion-icon>
                        Sair
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>