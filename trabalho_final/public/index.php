<?php
$url = $_GET['url'] ?? '/';
$url = rtrim($url, '/');

switch ($url) {
    case 'anunciante/cadastrar':
        require_once __DIR__ . '/../app/controllers/AnuncianteController.php';
        $controller = new AnuncianteController();
        $controller->cadastrar();
        break;
    
    case 'anunciante/login':
        require_once __DIR__ . '/../app/controllers/AnuncianteController.php';
        $controller = new AnuncianteController();
        $controller->login();
        break;

    case 'anuncio/criar':
        require_once __DIR__ . '/../app/controllers/AnuncioController.php';
        $controller = new AnuncioController();
        $controller->criar();
        break;

    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Rota não encontrada.']);
        break;
}