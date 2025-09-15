<?php

require_once __DIR__ . '/../core/conexaoMysql.php';

class Anunciante
{
    public function create($nome, $cpf, $email, $senha, $telefone)
    {
        try {
        
            $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

            $sql = "INSERT INTO Anunciante (Nome, CPF, Email, SenhaHash, Telefone) VALUES (:nome, :cpf, :email, :senha_hash, :telefone)";

            $pdo = Database::connect();
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':senha_hash', $senhaHash, PDO::PARAM_STR);
            $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);

            $stmt->execute();

            return $pdo->lastInsertId();

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception("CPF ou E-mail já cadastrado no sistema.");
            }
            throw $e;
        }
    }

    public function findByEmail($email)
    {
        $sql = "SELECT Id, Nome, Email, SenhaHash FROM Anunciante WHERE Email = :email LIMIT 1";
        $pdo = Database::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
