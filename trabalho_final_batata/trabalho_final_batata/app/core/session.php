<?php

/**
 * Inicia uma sessão segura ou resume a sessão atual.
 * Configura o cookie para ser HttpOnly.
 */
function startSecureSession()
{
    $cookieParams = session_get_cookie_params();
    $cookieParams['httponly'] = true;
    session_set_cookie_params($cookieParams);

    session_start();
    session_regenerate_id(true); // Previne Session Fixation
}

/**
 * Verifica se o usuário está logado. Se não estiver,
 * redireciona para a página de login e encerra o script.
 */
function requireLogin()
{
    if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
        // Redireciona para a página de login
        header("Location: login.html");
        exit();
    }
}