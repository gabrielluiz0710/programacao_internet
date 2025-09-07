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

        // Validação básica dos dados recebidos
        if (empty($nome) || empty($cpf) || empty($email) || empty($senha)) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Dados incompletos. Por favor, preencha todos os campos obrigatórios.']);
            return;
        }

        $anunciante = new Anunciante();
        try {
            // Os parâmetros passados para o método 'create' agora são as variáveis que pegamos de $_POST
            $sucesso = $anunciante->create(
                $nome,
                $cpf,
                $email,
                $senha,
                $telefone
            );

            if ($sucesso) {
                echo json_encode(['success' => true, 'message' => 'Cadastro realizado com sucesso!']);
            }
            // Se o método 'create' falhar sem lançar uma exceção (caso raro), podemos adicionar um 'else'
            // else {
            //     http_response_code(500); // Internal Server Error
            //     echo json_encode(['success' => false, 'message' => 'Ocorreu um erro inesperado ao salvar os dados.']);
            // }

        } catch (Exception $e) {
            http_response_code(409); // Conflict (ex: e-mail ou CPF já existe)
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
