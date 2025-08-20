<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

try {
  // SQL para selecionar as marcas distintas, ordenadas alfabeticamente
  $sql = <<<SQL
    SELECT DISTINCT marca
    FROM veiculo
    ORDER BY marca
    SQL;

  // O método query executa a consulta e retorna um objeto PDOStatement
  $stmt = $pdo->query($sql);

  // fetchAll com PDO::FETCH_COLUMN retorna um array simples com os valores da primeira coluna
  $marcas = $stmt->fetchAll(PDO::FETCH_COLUMN);
  
  // Define o cabeçalho da resposta para indicar que o conteúdo é JSON
  header('Content-type: application/json');
  // Converte o array de marcas para o formato JSON e o envia como resposta
  echo json_encode($marcas);

} catch (Exception $e) {
  // Em caso de erro, retorna uma resposta de erro no formato JSON
  header('Content-type: application/json');
  http_response_code(500);
  echo json_encode(['erro' => 'Ocorreu um erro no servidor: ' . $e->getMessage()]);
  exit();
}
?>