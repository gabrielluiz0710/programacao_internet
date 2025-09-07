<?php

require_once __DIR__ . '/../models/Anunciante.php';

class AnuncianteController
{
    public function cadastrar()
    {
        header('Content-Type: application/json');

        // Pega os dados enviados via POST (em formato JSON)
        $input = json_decode(file_get_contents('php://input'), true);

        // Validação básica dos dados recebidos
        if (empty($input['nome']) || empty($input['cpf']) || empty($input['email']) || empty($input['senha'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Dados incompletos. Por favor, preencha todos os campos obrigatórios.']);
            return;
        }

        $anunciante = new Anunciante();
        try {
            $sucesso = $anunciante->create(
                $input['nome'],
                $input['cpf'],
                $input['email'],
                $input['senha'],
                $input['telefone']
            );

            if ($sucesso) {
                echo json_encode(['success' => true, 'message' => 'Cadastro realizado com sucesso!']);
            }
        } catch (Exception $e) {
            http_response_code(409); // Conflict (ex: e-mail já existe)
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}