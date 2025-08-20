<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

try {
  $sql = <<<SQL
    SELECT DISTINCT marca
    FROM veiculo
    ORDER BY marca
    SQL;

  $stmt = $pdo->query($sql);

  $marcas = $stmt->fetchAll(PDO::FETCH_COLUMN);
  
  header('Content-type: application/json');
  echo json_encode($marcas);

} catch (Exception $e) {
  header('Content-type: application/json');
  http_response_code(500);
  echo json_encode(['erro' => 'Ocorreu um erro no servidor: ' . $e->getMessage()]);
  exit();
}
?>