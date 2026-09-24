<?php
session_start();
require_once "../../infra/conexao.php";

$mensagem = "";

if (isset($_GET["sucesso"]) && $_GET["sucesso"] == "1") {
    $mensagem = "Usuário excluído com sucesso!";
}

$sql = "SELECT usuario.id_usuario,
               usuario.nome,
               cargo.nome_cargo,
               usuario.email,
               usuario.telefone
        FROM usuario
        INNER JOIN cargo ON usuario.id_cargo = cargo.id_cargo";

$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários cadastrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../../style/style.css">
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
                    <li class="nav-item">
                        <a class="nav-link active" href="visualizar-user.php">Usuários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../relatorio/visualizar-relatorio.php">Relatórios</a>
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
                        <a class="btn btn-outline-light btn-sm d-flex align-items-center gap-2" href="../tela-login.php">
                            <ion-icon name="log-out-outline"></ion-icon>
                            <span>Sair</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Lista de usuários cadastrados</h1>
            <a href="cadastrar-user.php" class="btn d-flex align-items-center gap-2" style="background-color: #003399; border-color: #003399; color: white;">
                <ion-icon name="add-circle"></ion-icon>
                Novo usuário
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Cargo</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado && $resultado->num_rows > 0) { ?>
                                <?php while ($usuario = $resultado->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($usuario["nome"]) ?></td>
                                        <td><?= htmlspecialchars($usuario["nome_cargo"]) ?></td>
                                        <td><?= htmlspecialchars($usuario["email"]) ?></td>
                                        <td><?= htmlspecialchars($usuario["telefone"]) ?></td>
                                        <td>
                                            <a href="editar-user.php?id=<?= $usuario["id_usuario"] ?>" class="btn btn-primary btn-sm">
                                                <ion-icon name="pencil"></ion-icon>
                                                Editar
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="abrirAviso(<?= $usuario['id_usuario'] ?>)">
                                                <ion-icon name="trash"></ion-icon>
                                                Excluir
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Nenhum usuário cadastrado.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
                        <button type="button" class="btn btn-secondary px-5" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <a id="btnConfirmarExclusao" href="#" class="btn btn-danger px-5">
                            Excluir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($mensagem != "") { ?>
        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
            <?= htmlspecialchars($mensagem) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../script/botoes.js"></script>
</body>

</html>