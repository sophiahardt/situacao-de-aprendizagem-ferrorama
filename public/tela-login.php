<html lang="en">

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
                        <img src="../assets/img/logo.png" alt="Logo" class="img-fluid">
                    </div>

                    <h5 class="text-center fw-bold mb-5">Sistema Ferroviário Inteligente</h5>
                    <h2 class="text-center fw-bold mb-4">Login</h2>

                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Digite seu email">
                        </div>

                        <div class="mb-4">
                            <label for="senha" class="form-label fw-bold">Senha</label>
                            <input type="password" class="form-control" id="senha" placeholder="Digite sua senha">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-5">Entrar</button>
                        </div>
                    </form>

                    <p id="mensagem" class="text-center mt-3"></p>
                </div>
            </div>
        </div>
    </div>

    <script src="../script/validacao.js"></script>
    <script src="../script/scripts.js"></script>

</body>

</html>