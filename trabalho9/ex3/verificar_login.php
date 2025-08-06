<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailLogin = trim($_POST['email']);
    $senhaLogin = trim($_POST['senha']);

    $arquivo = "usuarios.txt";
    $loginSucesso = false;

    if (file_exists($arquivo)) {
        $handle = fopen($arquivo, "r");
        if ($handle) {
            while (($linha = fgets($handle)) !== false) {
                // separa email e hash
                $dados = explode(";", trim($linha));

                if (count($dados) == 2) {
                    $emailArmazenado = $dados[0];
                    $hashArmazenado = $dados[1];

                    if ($emailLogin === $emailArmazenado) {
                        // compara as senhas
                        if (password_verify($senhaLogin, $hashArmazenado)) {
                            $loginSucesso = true;
                            break; 
                        }
                    }
                }
            }
            fclose($handle);
        }
    }

    if ($loginSucesso) {
        // redireciona para sucesso
        header('Location: pagina_sucesso.html');
        exit();
    } else {
        // erro: redireciona para login e passa o erro
        header('Location: form_login.php?erro=1');
        exit();
    }
} else {
    header('Location: index.html');
    exit();
}
?>