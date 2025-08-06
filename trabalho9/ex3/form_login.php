<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="card-title">Testar Login</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_GET['erro'])) {
                            echo '<div class="alert alert-danger">E-mail ou senha inválidos. Tente novamente.</div>';
                        }
                        ?>
                        <form action="verificar_login.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">Entrar</button>
                            </div>
                        </form>
                    </div>
                     <div class="card-footer">
                         <a href="index.html">Voltar ao Menu</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>