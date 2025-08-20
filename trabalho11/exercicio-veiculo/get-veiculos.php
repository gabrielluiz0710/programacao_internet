<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

$modelo = $_GET['modelo'] ?? '';

try {
  $sql = <<<SQL
    SELECT modelo, ano, cor, quilometragem
    FROM veiculo
    WHERE modelo = ?
    SQL;
  
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$modelo]);

  $veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  header('Content-type: application/json');
  echo json_encode($veiculos);

} catch (Exception $e) {
  header('Content-type: application/json');
  http_response_code(500);
  echo json_encode(['erro' => 'Ocorreu um erro no servidor: ' . $e->getMessage()]);
  exit();
}
?>