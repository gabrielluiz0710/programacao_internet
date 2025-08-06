<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Usuários Cadastrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Usuários Cadastrados</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>E-mail</th>
                    <th>Hash da Senha</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $arquivo = "usuarios.txt";
                if (file_exists($arquivo)) {
                    $handle = fopen($arquivo, "r");
                    if ($handle) {
                        while (($linha = fgets($handle)) !== false) {
                            $dados = explode(";", trim($linha));
                            if (count($dados) == 2) {
                                $email = htmlspecialchars($dados[0]);
                                $hash = htmlspecialchars($dados[1]);
                                echo "<tr><td>{$email}</td><td>{$hash}</td></tr>";
                            }
                        }
                        fclose($handle);
                    }
                } else {
                    echo '<tr><td colspan="2" class="text-center">Nenhum usuário cadastrado.</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <a href="index.html" class="btn btn-primary mt-3">Voltar ao Menu</a>
    </div>
</body>
</html>