<?php
// Sempre inicie a sessão antes de manipulá-la
session_start();

// 1. Limpa todas as variáveis da sessão
session_unset();

// 2. Destrói a sessão no servidor
session_destroy();

// 3. Remove o cookie de sessão do navegador do cliente (boa prática)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Redireciona para a página de login
header("Location: login.php");
exit();