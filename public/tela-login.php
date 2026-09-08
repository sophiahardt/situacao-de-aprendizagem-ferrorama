<?php

session_start();

require_once "../infra/conexao.php";

$mensagem = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"] ?? "";
    $senha = $_POST["senha"] ?? "";

    if ($email == "" || $senha == "") {

        $mensagem = "Preencha todos os campos.";

    } else {

        $sql = "SELECT * FROM usuario WHERE email = ?";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {

            $usuario = $resultado->fetch_assoc();

            if (password_verify($senha, $usuario["senha_hash"])) {

                $_SESSION["id_usuario"] = $usuario["id_usuario"];
                $_SESSION["nome_usuario"] = $usuario["nome"];
                $_SESSION["id_cargo"] = $usuario["id_cargo"];

                header("Location: tela-geral-home.php");
                exit;

            } else {

                $mensagem = "Email ou senha incorretos.";

            }

        } else {

            $mensagem = "Email ou senha incorretos.";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../style/style.css">

</head>

<body class="fundo-tela-login">

    <div class="container-fluid p-0">

        <div class="fundo">

            <div class="painel d-flex align-items-center">

                <div class="login-box w-100 text-white">

                    <div class="logo text-center mb-4">

                        <img src="../assets/img/logo.png"
                             alt="Logo"
                             class="img-fluid">

                    </div>

                    <h5 class="text-center fw-bold mb-5">
                        Sistema Ferroviário Inteligente
                    </h5>

                    <h2 class="text-center fw-bold mb-4">
                        Login
                    </h2>

                    <?php if ($mensagem != "") { ?>

                        <p class="text-center mt-3 text-warning">
                            <?= $mensagem ?>
                        </p>

                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label for="email" class="form-label fw-bold">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                id="email"
                                placeholder="Digite seu email"
                                required
                            >

                        </div>

                        <div class="mb-4">

                            <label for="senha" class="form-label fw-bold">
                                Senha
                            </label>

                            <input
                                type="password"
                                name="senha"
                                class="form-control"
                                id="senha"
                                placeholder="Digite sua senha"
                                required
                            >

                        </div>

                        <div class="text-center">

                            <button
                                type="submit"
                                class="btn btn-primary px-5"
                            >
                                Entrar
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</body>

</html>