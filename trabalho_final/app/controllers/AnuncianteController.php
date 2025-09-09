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
}
