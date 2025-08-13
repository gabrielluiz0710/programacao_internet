<?php

class Paciente
{
  static function Create($pdo, $nome, $sexo, $email, $peso, $altura, $tipoSanguineo)
  {
    try {
      $pdo->beginTransaction();

      // Insere os dados na tabela Pessoa
      $stmt1 = $pdo->prepare(
        <<<SQL
        INSERT INTO Pessoa (Nome, Sexo, Email)
        VALUES (?, ?, ?)
        SQL
      );
      $stmt1->execute([$nome, $sexo, $email]);

      // Pega o ID da pessoa
      $idNovaPessoa = $pdo->lastInsertId();

      // Insere os dados na tabela 'Paciente' usando o ID
      $stmt2 = $pdo->prepare(
        <<<SQL
        INSERT INTO Paciente (IdPessoa, Peso, Altura, TipoSanguineo)
        VALUES (?, ?, ?, ?)
        SQL
      );
      $stmt2->execute([$idNovaPessoa, $peso, $altura, $tipoSanguineo]);

      $pdo->commit();

    } catch (Exception $e) {
      $pdo->rollBack();
      throw $e;
    }
  }

  static function GetAll($pdo)
  {
    $stmt = $pdo->query(
      <<<SQL
      SELECT 
        Pessoa.Nome, Pessoa.Sexo, Pessoa.Email,
        Paciente.Peso, Paciente.Altura, Paciente.TipoSanguineo
      FROM 
        Pessoa
      INNER JOIN 
        Paciente ON Pessoa.Id = Paciente.IdPessoa
      LIMIT 30
      SQL
    );
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
}