<?php

function exitWhenNotLoggedIn()
{ 
  // se não houver variável de sessão 'loggedIn', redireciona p/ login
  if (!isset($_SESSION['loggedIn'])) {
    header("Location: index.html");
    exit();  
  }
}
