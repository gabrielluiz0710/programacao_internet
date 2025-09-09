<?php
// Inclui nosso novo arquivo de sessão
require_once __DIR__ . '/../app/core/session.php';

// Inicia a sessão de forma segura
startSecureSession();

// Exige que o usuário esteja logado para ver esta página
requireLogin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AutoFácil - Central do Usuário</title>
  <link rel="icon" type="image/svg+xml" href="./images/icon.svg" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="./css/header-footer.css">
  <link rel="stylesheet" href="./css/central-user.css">
</head>

<body>
  <header>
    <div class="container">
      <a href="index.html" class="logo">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
          <path
            d="M18.9 12.19C18.78 11.5 18.25 11 17.5 11H6.5C5.75 11 5.22 11.5 5.1 12.19L3 18V21H4.5C4.78 21 5 20.78 5 20.5V20H19V20.5C19 20.78 19.22 21 19.5 21H21V18L18.9 12.19M6.5 13H17.5L18.33 15.5H5.67L6.5 13M8.5 17C9.33 17 10 16.33 10 15.5C10 14.67 9.33 14 8.5 14C7.67 14 7 14.67 7 15.5C7 16.33 7.67 17 8.5 17M15.5 17C16.33 17 17 16.33 17 15.5C17 14.67 16.33 14 15.5 14C14.67 14 14 14.67 14 15.5C14 16.33 14.67 17 15.5 17M5 10H19V8C19 7.45 18.55 7 18 7H6C5.45 7 5 7.45 5 8V10Z">
          </path>
        </svg>
        <span class="logo-text">AutoFácil</span>
      </a>
    </div>
  </header>

  <nav>
    <div class="container">
      <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="login.html">Login</a></li>
        <li><a href="cadastro.html">Cadastre-se</a></li>
      </ul>
    </div>
  </nav>

  <main>
    <div>
      <h1>Central do Usuário</h1>
      <h2>
        Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
      </h2>
      <span></span>
      <div>
        <a href="index.html"><button>Home</button></a>
        <a href="criar-anuncio.html"><button>Crie um novo anúncio</button></a>
        <a href="meus-anuncios.html"><button>Anúncios criados</button></a>
        <a href="logout.php"><button>Logoff</button></a>
      </div>
    </div>
  </main>

  <footer>
    <div class="container">
      <p>&copy; 2025 AutoFácil. Todos os direitos reservados.</p>
    </div>
  </footer>

</body>

</html>