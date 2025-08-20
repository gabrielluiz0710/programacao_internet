<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

// Resgata a marca enviada via GET, com uma verificação de segurança
$marca = $_GET['marca'] ?? '';

try {
  // SQL para selecionar os modelos distintos de uma marca específica
  // Usamos um prepared statement para prevenir injeção de SQL
  $sql = <<<SQL
    SELECT DISTINCT modelo
    FROM veiculo
    WHERE marca = ?
    ORDER BY modelo
    SQL;

  $stmt = $pdo->prepare($sql);
  $stmt->execute([$marca]);

  $modelos = $stmt->fetchAll(PDO::FETCH_COLUMN);
  
  header('Content-type: application/json');
  echo json_encode($modelos);

} catch (Exception $e) {
  header('Content-type: application/json');
  http_response_code(500);
  echo json_encode(['erro' => 'Ocorreu um erro no servidor: ' . $e->getMessage()]);
  exit();
}
?>