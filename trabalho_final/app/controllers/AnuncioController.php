<?php
require_once __DIR__ . '/../models/Anuncio.php';
require_once __DIR__ . '/../core/session.php';

class AnuncioController
{
    public function criar()
    {
        header('Content-Type: application/json');
        startSecureSession();

        // Dupla verificação: a página já é protegida, mas a rota também deve ser.
        if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
            http_response_code(403); // Forbidden
            echo json_encode(['success' => false, 'message' => 'Acesso negado.']);
            return;
        }

        // Validação básica dos dados do formulário
        $requiredFields = ['marca', 'modelo', 'ano', 'valor', 'estado', 'cidade', 'descricao'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                http_response_code(400); // Bad Request
                echo json_encode(['success' => false, 'message' => "O campo '$field' é obrigatório."]);
                return;
            }
        }
        
        // Coleta as fotos enviadas
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

            // Se a transação foi bem-sucedida
            echo json_encode([
                'success' => true,
                'message' => 'Anúncio criado com sucesso!',
                'redirectUrl' => 'meus-anuncios.php' // Lembre-se de criar e proteger esta página
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
        
        // A requisição será POST para segurança
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
}