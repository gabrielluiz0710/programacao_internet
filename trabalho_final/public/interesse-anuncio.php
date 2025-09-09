<?php
require_once __DIR__ . '/../app/core/session.php';
require_once __DIR__ . '/../app/models/Anuncio.php';

startSecureSession();
requireLogin();

$adId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$ownerId = $_SESSION['user_id'];

if (!$adId) {
    die("Anúncio não encontrado.");
}

$anuncioModel = new Anuncio();
// Busca tanto os dados do anúncio quanto os interesses
$anuncio = $anuncioModel->findAdByIdAndOwner($adId, $ownerId);
$interesses = $anuncioModel->findInterestsByAdAndOwner($adId, $ownerId);

if (!$anuncio) {
    die("Anúncio não encontrado ou você não tem permissão para visualizá-lo.");
}

$precoFormatado = number_format($anuncio['Valor'], 2, ',', '.');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoFácil - Interesses do Anúncio</title>
    <link rel="icon" type="image/svg+xml" href="./images/icon.svg" />
    <link rel="stylesheet" href="./css/header-footer.css">
    <link rel="stylesheet" href="./css/interesse-anuncio.css">
</head>
<body>

    <header>
        <div class="container">
            <a href="index.html" class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18.9 12.19C18.78 11.5 18.25 11 17.5 11H6.5C5.75 11 5.22 11.5 5.1 12.19L3 18V21H4.5C4.78 21 5 20.78 5 20.5V20H19V20.5C19 20.78 19.22 21 19.5 21H21V18L18.9 12.19M6.5 13H17.5L18.33 15.5H5.67L6.5 13M8.5 17C9.33 17 10 16.33 10 15.5C10 14.67 9.33 14 8.5 14C7.67 14 7 14.67 7 15.5C7 16.33 7.67 17 8.5 17M15.5 17C16.33 17 17 16.33 17 15.5C17 14.67 16.33 14 15.5 14C14.67 14 14 14.67 14 15.5C14 16.33 14.67 17 15.5 17M5 10H19V8C19 7.45 18.55 7 18 7H6C5.45 7 5 7.45 5 8V10Z"></path>
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
        <div class="titulo-container">
            <h1 id="titulo-anuncio">Interesses no Anúncio: ...</h1>
            <div id="info-anuncio">
                 <span><?php echo htmlspecialchars($anuncio['Marca']); ?></span>
                <span><?php echo htmlspecialchars($anuncio['Modelo']); ?></span>
                <span><?php echo htmlspecialchars($anuncio['Ano']); ?></span>
                <span>R$ <?php echo $precoFormatado; ?></span>
            </div>
        </div>

        <?php if (empty($interesses)): ?>
            <p class="info-message">Ainda não há nenhuma demonstração de interesse para este anúncio.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Data e Hora</th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Mensagem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($interesses as $interesse): ?>
                        <tr>
                            <td data-label="Data"><?php echo date('d/m/Y H:i', strtotime($interesse['DataHora'])); ?></td>
                            <td data-label="Nome"><?php echo htmlspecialchars($interesse['Nome']); ?></td>
                            <td data-label="Telefone"><?php echo htmlspecialchars($interesse['Telefone']); ?></td>
                            <td data-label="Mensagem"><?php echo nl2br(htmlspecialchars($interesse['Mensagem'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 AutoFácil. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>