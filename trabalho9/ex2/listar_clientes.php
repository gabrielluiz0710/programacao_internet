<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <style>
        .word-break {
            word-wrap: break-word;
            word-break: break-all;
            max-width: 200px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col">
                <h2 class="mb-4">Clientes Cadastrados</h2>
                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nome Completo</th>
                                <th>CPF</th>
                                <th>E-mail</th>
                                <th>Senha (Hash)</th>
                                <th>CEP</th>
                                <th>Endereço</th>
                                <th>Bairro</th>
                                <th>Cidade</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $arquivo = "clientes.txt";

                            if (file_exists($arquivo) && is_readable($arquivo)) {
                                $handle = fopen($arquivo, "r");

                                if ($handle) {
                                    while (($linha = fgets($handle)) !== false) {
                                        $dados = explode(";", trim($linha));

                                        if (count($dados) == 9) {
                                            $nome = htmlspecialchars($dados[0]);
                                            $cpf = htmlspecialchars($dados[1]);
                                            $email = htmlspecialchars($dados[2]);
                                            $senha = htmlspecialchars($dados[3]);
                                            $cep = htmlspecialchars($dados[4]);
                                            $endereco = htmlspecialchars($dados[5]);
                                            $bairro = htmlspecialchars($dados[6]);
                                            $cidade = htmlspecialchars($dados[7]);
                                            $estado = htmlspecialchars($dados[8]);

                                            echo "<tr>";
                                            echo "<td>{$nome}</td>";
                                            echo "<td>{$cpf}</td>";
                                            echo "<td>{$email}</td>";
                                            echo "<td class='word-break'>{$senha}</td>";
                                            echo "<td>{$cep}</td>";
                                            echo "<td>{$endereco}</td>";
                                            echo "<td>{$bairro}</td>";
                                            echo "<td>{$cidade}</td>";
                                            echo "<td>{$estado}</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    fclose($handle);
                                }
                            } else {
                                echo '<tr><td colspan="9" class="text-center">Nenhum cliente cadastrado ainda.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <a href="index.html" class="btn btn-primary">Cadastrar Novo Cliente</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>