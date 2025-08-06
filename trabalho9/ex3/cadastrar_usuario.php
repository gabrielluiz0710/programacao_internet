<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Processando Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $senha = trim($_POST['senha']);

            if (!empty($email) && !empty($senha)) {
                $hashSenha = password_hash($senha, PASSWORD_DEFAULT);
                $arquivo = "usuarios.txt";

                $linha = "$email;$hashSenha" . PHP_EOL;

                if (file_put_contents($arquivo, $linha, FILE_APPEND | LOCK_EX)) {
                    echo '<div class="alert alert-success">Usuário cadastrado com sucesso!</div>';
                } else {
                    echo '<div class="alert alert-danger">Erro ao salvar o usuário.</div>';
                }
            } else {
                echo '<div class="alert alert-warning">Por favor, preencha todos os campos.</div>';
            }
        }
        ?>
        <a href="form_cadastro.html" class="btn btn-secondary">Cadastrar Outro</a>
        <a href="index.html" class="btn btn-primary">Voltar ao Menu</a>
    </div>
</body>
</html>