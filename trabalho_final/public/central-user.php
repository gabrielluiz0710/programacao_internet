<?php
require_once __DIR__ . '/../app/core/session.php';

startSecureSession();

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
        <li><a href="login.php">Central do Usuário</a></li>
        <li><a href="cadastro.html">Cadastre-se</a></li>
      </ul>
    </div>
  </nav>

  <main>
    <div class="user-panel">
      <div class="panel-header">
        <h1>Painel de Controle</h1>
        <p>Olá, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>! O que faremos hoje?</p>
      </div>

      <div class="panel-actions">
        <a href="criar-anuncio.php" class="action-button primary">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
            <path d="M11 11V5H13V11H19V13H13V19H11V13H5V11H11Z"></path>
          </svg>
          <span>Criar Novo Anúncio</span>
        </a>
        <a href="meus-anuncios.php" class="action-button">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
            <path d="M3 3H21V21H3V3ZM5 5V19H19V5H5ZM7 7H17V9H7V7ZM7 11H17V13H7V11ZM7 15H14V17H7V15Z"></path>
          </svg>
          <span>Meus Anúncios</span>
        </a>
      </div>

      <div class="panel-footer">
        <a href="index.html">Voltar para a Home</a>
        <span>•</span>
        <a href="logout.php">Sair (Logoff)</a>
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