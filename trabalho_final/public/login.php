<?php
// Inclui nosso arquivo de funções de sessão
require_once __DIR__ . '/../app/core/session.php';

// Inicia a sessão para podermos verificar se o usuário já está logado
startSecureSession();

// Se o usuário já estiver logado (ou seja, a sessão 'loggedIn' existe e é true),
// redireciona para a central do usuário e encerra o script.
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header("Location: central-user.php");
    exit();
}

// Se o usuário NÃO estiver logado, o script continua e o HTML abaixo é exibido normalmente.
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoFácil - Login</title>
    <link rel="icon" type="image/svg+xml" href="./images/icon.svg" />
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <header></header>

    <main>
        <h1>Login</h1>
        <p>Acesse sua conta para gerenciar e criar novos anúncios.</p>

        <form id="form-login" action="index.php?url=anunciante/login" method="post" novalidate>
            <div id="form-message" class="message"></div>
            <div>
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div>
                <button type="submit">Entrar</button>
            </div>
        </form>

        <p>Ainda não tem uma conta? <a href="cadastro.html">Cadastre-se agora!</a></p>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 AutoFácil. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="./js/login.js"></script>
</body>

</html>