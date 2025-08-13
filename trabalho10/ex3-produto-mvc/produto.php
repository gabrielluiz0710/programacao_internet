<?php

class Produto
{
  static function Create($pdo, $nome, $marca, $descricao)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      INSERT INTO produto (nome, marca, descricao)
      VALUES (?, ?, ?)
      SQL
    );

    $stmt->execute([$nome, $marca, $descricao]);

    return $pdo->lastInsertId();
  }

  static function Get($pdo, $id)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT id, nome, marca, descricao
      FROM produto
      WHERE id = ?
      SQL
    );

    $stmt->execute([$id]);
    if ($stmt->rowCount() == 0)
      throw new Exception("Produto não localizado");

    $produto = $stmt->fetch(PDO::FETCH_OBJ);
    return $produto;
  }

  static function GetFirst30($pdo)
  {
    $stmt = $pdo->query(
      <<<SQL
      SELECT id, nome, marca, descricao
      FROM produto
      LIMIT 30
      SQL
    );

    $arrayProdutos = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $arrayProdutos;
  }

  public static function Remove($pdo, $id)
  {
    $sql = <<<SQL
    DELETE 
    FROM produto
    WHERE id = ?
    LIMIT 1
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
  }
}
