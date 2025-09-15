<?php
require_once __DIR__ . '/../models/Anuncio.php';
require_once __DIR__ . '/../core/session.php';

class AnuncioController
{
    public function criar()
    {
        header('Content-Type: application/json');
        startSecureSession();

        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {

            die(json_encode([
                'success' => false, 
                'message' => 'DEBUG: A variável $_SESSION[\'user_id\'] está vazia ou não existe.'
            ]));
        }

        if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Acesso negado.']);
            return;
        }

        $requiredFields = ['marca', 'modelo', 'ano', 'valor', 'estado', 'cidade', 'descricao'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => "O campo $field é obrigatório."]);
                return;
            }
        }
        $fotos = [];
        if (!empty($_FILES['foto1'])) $fotos[] = $_FILES['foto1'];
        if (!empty($_FILES['foto2'])) $fotos[] = $_FILES['foto2'];
        if (!empty($_FILES['foto3'])) $fotos[] = $_FILES['foto3'];

        if (count($fotos) < 3) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'É necessário enviar pelo menos 3 fotos.']);
            return;
        }

        try {
            $anuncioModel = new Anuncio();
            $idAnunciante = $_SESSION['user_id'];
            
            $anuncioModel->createAdWithPhotos($_POST, $fotos, $idAnunciante);

            echo json_encode([
                'success' => true,
                'message' => 'Anúncio criado com sucesso!',
                'redirectUrl' => 'meus-anuncios.php'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function listarPorUsuario()
    {
        header('Content-Type: application/json');
        startSecureSession();

        if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Acesso negado.']);
            return;
        }

        try {
            $anuncioModel = new Anuncio();
            $idAnunciante = $_SESSION['user_id'];
            
            $anuncios = $anuncioModel->findByAnuncianteId($idAnunciante);

            echo json_encode(['success' => true, 'anuncios' => $anuncios]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao buscar anúncios: ' . $e->getMessage()]);
        }
    }

    public function remover()
    {
        header('Content-Type: application/json');
        startSecureSession();

        if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Acesso negado.']);
            return;
        }
        
        $adId = $_POST['id'] ?? null;
        $ownerId = $_SESSION['user_id'];

        if (!$adId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID do anúncio não fornecido.']);
            return;
        }

        try {
            $anuncioModel = new Anuncio();
            $anuncioModel->deleteAdByIdAndOwner($adId, $ownerId);

            echo json_encode(['success' => true, 'message' => 'Anúncio removido com sucesso.']);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getMarcas() {
        header('Content-Type: application/json');
        try {
            $anuncioModel = new Anuncio();
            $marcas = $anuncioModel->getDistinctField('Marca');
            echo json_encode(['success' => true, 'marcas' => $marcas]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Não foi possível buscar as marcas disponíveis: ' . $e->getMessage()]);
        }
    }

    public function getModelos() {
        header('Content-Type: application/json');
        try {
            $marca = $_GET['marca'] ?? '';
            if (empty($marca)) { throw new Exception('Marca não fornecida.'); }
            
            $anuncioModel = new Anuncio();
            $modelos = $anuncioModel->getDistinctField('Modelo', 'Marca', $marca);
            echo json_encode(['success' => true, 'modelos' => $modelos]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Não foi possível buscar os modelos disponíveis para a marca selecionada: ' . $e->getMessage()]);
        }
    }

    public function getCidades() {
        header('Content-Type: application/json');
        try {
            $marca = $_GET['marca'] ?? '';
            $modelo = $_GET['modelo'] ?? '';
            if (empty($marca) || empty($modelo)) { throw new Exception('Marca e/ou modelo não fornecidos.'); }

            $anuncioModel = new Anuncio();
            $cidades = $anuncioModel->getDistinctCities($marca, $modelo);
            echo json_encode(['success' => true, 'cidades' => $cidades]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Não foi possível buscar cidades com anúncios disponíveis: ' . $e->getMessage()]);
        }
    }

    public function buscar() {
        header('Content-Type: application/json');
        try {
            $filters = [
                'marca' => $_GET['marca'] ?? null,
                'modelo' => $_GET['modelo'] ?? null,
                'localizacao' => $_GET['localizacao'] ?? null,
            ];
            $anuncioModel = new Anuncio();
            $anuncios = $anuncioModel->searchAds($filters);
            echo json_encode(['success' => true, 'anuncios' => $anuncios]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro no processo de busca: ' . $e->getMessage()]);
        }
    }

    public function registrarInteresse()
    {
        header('Content-Type: application/json');

        $adId = $_POST['idAnuncio'] ?? null;
        $nome = $_POST['nome'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $mensagem = $_POST['mensagem'] ?? '';

        if (empty($adId) || empty($nome) || empty($telefone) || empty($mensagem)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
            return;
        }

        try {
            $anuncioModel = new Anuncio();
            $anuncioModel->addInteresse($adId, $nome, $telefone, $mensagem);

            echo json_encode(['success' => true, 'message' => 'Interesse registrado com sucesso! O vendedor entrará em contato.']);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Não foi possível registrar o interesse. Tente novamente.']);
        }
    }
}