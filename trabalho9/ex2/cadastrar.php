<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    $nome = trim($_POST['nome']);
                    $cpf = trim($_POST['cpf']);
                    $email = trim($_POST['email']);
                    $senha = trim($_POST['senha']);
                    $cep = trim($_POST['cep']);
                    $endereco = trim($_POST['endereco']);
                    $bairro = trim($_POST['bairro']);
                    $cidade = trim($_POST['cidade']);
                    $estado = trim($_POST['estado']);

                    if (!empty($nome) && !empty($cpf) && !empty($email) && !empty($senha) && !empty($estado)) {
                        
                        $arquivo = "clientes.txt";
                        $hashSenha = password_hash($senha, PASSWORD_DEFAULT);


                        $linha = "$nome;$cpf;$email;$hashSenha;$cep;$endereco;$bairro;$cidade;$estado" . PHP_EOL;

                        if (file_put_contents($arquivo, $linha, FILE_APPEND | LOCK_EX)) {
                            echo '<div class="alert alert-success" role="alert">';
                            echo '<h4 class="alert-heading">Sucesso!</h4>';
                            echo '<p>Cliente cadastrado com sucesso.</p>';
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-danger" role="alert">';
                            echo '<h4 class="alert-heading">Erro!</h4>';
                            echo '<p>Não foi possível salvar os dados no servidor. Verifique as permissões do arquivo.</p>';
                            echo '</div>';
                        }

                    } else {
                        echo '<div class="alert alert-warning" role="alert">';
                        echo '<h4 class="alert-heading">Atenção!</h4>';
                        echo '<p>Por favor, preencha todos os campos obrigatórios.</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="alert alert-info" role="alert">';
                    echo '<p>Por favor, preencha o formulário para se cadastrar.</p>';
                    echo '</div>';
                }
                ?>
                <div class="mt-4">
                    <a href="index.html" class="btn btn-secondary">Voltar ao Formulário</a>
                    <a href="listar_clientes.php" class="btn btn-primary">Ver Lista de Clientes</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>