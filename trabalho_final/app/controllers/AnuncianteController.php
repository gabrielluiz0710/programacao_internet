<?php

require_once __DIR__ . '/../models/Anunciante.php';

class AnuncianteController
{
    public function cadastrar()
    {
        header('Content-Type: application/json');

        $nome = $_POST['nome'] ?? '';
        $cpf = $_POST['cpf'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $telefone = $_POST['telefone'] ?? '';

        if (empty($nome) || empty($cpf) || empty($email) || empty($senha)) {
            http_response_code(response_code: 400);
            echo json_encode(['success' => false, 'message' => 'Dados incompletos.']);
            return;
        }

        $anunciante = new Anunciante();
        try {
            $novoId = $anunciante->create($nome, $cpf, $email, $senha, $telefone);

            if ($novoId) {
                
                $cookieParams = session_get_cookie_params();
                $cookieParams['httponly'] = true;
                session_set_cookie_params($cookieParams);

                session_start();

                session_regenerate_id(true);

                $_SESSION = [];
                $_SESSION['loggedIn'] = true;
                $_SESSION['user_id'] = $novoId;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_name'] = $nome; 

                echo json_encode([
                    'success' => true,
                    'message' => 'Cadastro realizado com sucesso!',
                    'redirectUrl' => 'central-user.php' 
                ]);

            }
        } catch (Exception $e) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function login()
    {
        header('Content-Type: application/json');
        
        require_once __DIR__ . '/../core/session.php';

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'E-mail e senha são obrigatórios.']);
            return;
        }

        try {
            $anuncianteModel = new Anunciante();
            $user = $anuncianteModel->findByEmail($email);

            // verificação de senha
            if (!$user || !password_verify($senha, $user['SenhaHash'])) {
                http_response_code(401); // 401 Unauthorized
                echo json_encode(['success' => false, 'message' => 'E-mail ou senha inválidos.']);
                return;
            }
            
            startSecureSession();
            
            $_SESSION = [];
            $_SESSION['loggedIn'] = true;
            $_SESSION['user_id'] = $user['Id'];
            $_SESSION['user_email'] = $user['Email'];
            $_SESSION['user_name'] = $user['Nome'];

            echo json_encode(['success' => true, 'redirectUrl' => 'central-user.php']);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro interno do servidor. Tente novamente mais tarde.']);
        }
    }
}
