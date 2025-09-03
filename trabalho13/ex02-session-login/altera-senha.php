<?php

require "conexaoMysql.php";
require "sessionVerification.php";

session_start();
exitWhenNotLoggedIn();

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token'])
  exit('Operação não permitida.');

$pdo = mysqlConnect();
$email = $_POST['email'] ?? "";
$novaSenha = $_POST['novaSenha'] ?? "";
$senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

try {
  $stmt = $pdo->prepare(
    <<<SQL
      UPDATE cliente
      SET senhaHash = ?
      WHERE email = ?
    SQL
  );
  $stmt->execute([$senhaHash, $email]);
  header("location: sucesso.html");
}
catch (Exception $e) {
  exit('Falha inesperada: ' . $e->getMessage());
}