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
            $sucesso = $anunciante->create($nome, $cpf, $email, $senha, $telefone);

            if ($sucesso) {
                // --- INÍCIO DA MODIFICAÇÃO ---

                // 1. Configura o cookie de sessão para ser mais seguro (HttpOnly)
                $cookieParams = session_get_cookie_params();
                $cookieParams['httponly'] = true;
                session_set_cookie_params($cookieParams);

                // 2. Inicia a sessão
                session_start();

                // 3. Regenera o ID da sessão para prevenir Session Fixation
                session_regenerate_id(true);

                // 4. Guarda os dados do usuário na sessão
                $_SESSION['loggedIn'] = true;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_name'] = $nome; // Vamos guardar o nome para dar um "Olá, Fulano!"

                // 5. Adiciona a URL de redirecionamento na resposta JSON
                echo json_encode([
                    'success' => true,
                    'message' => 'Cadastro realizado com sucesso!',
                    'redirectUrl' => 'central-user.php' // ATENÇÃO: A PÁGINA PRECISA SER .PHP
                ]);

                // --- FIM DA MODIFICAÇÃO ---
            }
        } catch (Exception $e) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function login()
    {
        header('Content-Type: application/json');
        
        // Inclui as funções de sessão que já criamos
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

            // Verifica se o usuário existe E se a senha está correta
            // Usamos uma única mensagem de erro por segurança (evita enumeração de usuários)
            if (!$user || !password_verify($senha, $user['SenhaHash'])) {
                http_response_code(401); // 401 Unauthorized
                echo json_encode(['success' => false, 'message' => 'E-mail ou senha inválidos.']);
                return;
            }
            
            // Se chegou aqui, os dados estão corretos. Inicia a sessão!
            startSecureSession();
            
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
