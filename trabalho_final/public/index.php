<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

    case 'anuncio/listarPorUsuario':
        require_once __DIR__ . '/../app/controllers/AnuncioController.php';
        $controller = new AnuncioController();
        $controller->listarPorUsuario();
        break;

    case 'anuncio/remover':
        require_once __DIR__ . '/../app/controllers/AnuncioController.php';
        $controller = new AnuncioController();
        $controller->remover();
        break;

    case 'anuncio/marcas':
        require_once __DIR__ . '/../app/controllers/AnuncioController.php';
        $controller = new AnuncioController();
        $controller->getMarcas();
        break;
    
    case 'anuncio/modelos':
        require_once __DIR__ . '/../app/controllers/AnuncioController.php';
        $controller = new AnuncioController();
        $controller->getModelos();
        break;

    case 'anuncio/cidades':
        require_once __DIR__ . '/../app/controllers/AnuncioController.php';
        $controller = new AnuncioController();
        $controller->getCidades();
        break;

    case 'anuncio/buscar':
        require_once __DIR__ . '/../app/controllers/AnuncioController.php';
        $controller = new AnuncioController();
        $controller->buscar();
        break;

    case 'anuncio/registrarInteresse':
        require_once __DIR__ . '/../app/controllers/AnuncioController.php';
        $controller = new AnuncioController();
        $controller->registrarInteresse();
        break;

    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Rota não encontrada.']);
        break;
}