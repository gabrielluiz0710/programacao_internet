<?php

// inicia a sessão
// inicia/retoma sessão
session_start();

// apaga as variáveis de sessão de $_SESSION
// limpa variáveis da sessão
session_unset();

// destrói a sessão e as variáveis fisicamente (em arquivo)
// destrói sessão no servidor
session_destroy();

// exclui o cookie da sessão no computador do usuário
// apaga cookie PHPSESSID do cliente
setcookie(session_name(), "", 1, "/");

// redireciona o usuário para a página de login
// redireciona para página de login
header('Location: index.html');
exit();