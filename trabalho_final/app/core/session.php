<?php

function startSecureSession()
{
    $cookieParams = session_get_cookie_params();
    $cookieParams['httponly'] = true;
    session_set_cookie_params($cookieParams);

    session_start();
    session_regenerate_id(true);
}

function requireLogin()
{
    if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
        header("Location: login.html");
        exit();
    }
}