<?php

require_once __DIR__ . '/../core/Database.php';

class Anunciante
{
    public function create($nome, $cpf, $email, $senha, $telefone)
    {
        try {
            // A senha NUNCA deve ser salva como texto puro.
            // A função password_hash cria um hash seguro.
            $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

            $sql = "INSERT INTO Anunciante (Nome, CPF, Email, SenhaHash, Telefone) VALUES (:nome, :cpf, :email, :senha_hash, :telefone)";

            $pdo = Database::connect();
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':senha_hash', $senhaHash, PDO::PARAM_STR);
            $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            // O código de erro 23000 geralmente indica uma violação de chave única (UNIQUE), como CPF ou Email duplicado.
            if ($e->getCode() == 23000) {
                // Lança uma nova exceção com uma mensagem mais amigável
                throw new Exception("CPF ou E-mail já cadastrado no sistema.");
            }
            // Para outros erros, lança a exceção original
            throw $e;
        }
    }
}