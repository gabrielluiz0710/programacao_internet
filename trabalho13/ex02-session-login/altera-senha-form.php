<?php
require "sessionVerification.php";
session_start();
exitWhenNotLoggedIn();
?>

<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ajax</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">

  <style>
    html {
      margin: 0;
      padding: 0;
    }

    body {
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
      background-image: url("images/bg2.jpg");
      background-repeat: no-repeat;
      background-size: cover;
      margin: 0;
      padding: 0;
    }

    .container {
      position: relative;
      height: 100vh;
    }

    main {
      padding: 2rem;
      padding-top: 2rem;
      width: 60%;
      border: 0.5px solid lightgray;
      border-radius: 5px;
      background-color: #fff;
      box-shadow: 5px 5px 5px gray;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    form {
      margin-bottom: 2rem;
    }

    main>h3 {
      text-align: center;
      color: blue;
      margin-bottom: 2rem;
    }

    #loginFailMsg {
      color: red;
      text-align: center;
    }

    .hide {
      display: none;
    }
  </style>
</head>

<body>

  <div class="container">
    <main>
      <h3>Alteração de senha</h3>
      <form class="row g-3" action="altera-senha.php" method="POST">
        
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">

        <div class="col-sm-12">
          <label for="email" class="form-label">E-mail</label>
          <input type="email" name="email" class="form-control" id="email">
        </div>
        <div class="col-sm-12">
          <label for="novaSenha" class="form-label">Nova Senha</label>
          <input type="password" name="novaSenha" class="form-control" id="novaSenha">
        </div>
        <div class="col-sm-12 d-grid">
          <button class="btn btn-primary btn-block">Alterar</button>
        </div>
      </form>
    </main>
  </div>

</body>

</html>