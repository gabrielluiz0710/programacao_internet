<?php

require "conexaoMysql.php";
require "sessionVerification.php";

session_start();
exitWhenNotLoggedIn();

// confere se o token enviado pelo formulário é igual ao token salvo na sessão do usuário
// Se não houver ou se não bater, a operação é bloqueada
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


// QUESTAO 9:
// O uso de POST por si só NÃO protege contra ataques CSRF. Um site malicioso pode criar um formulário escondido que envia
// uma requisição POST para este script usando as credenciais já ativas do usuário. Por isso é necessário o token CSRF: 
// somente o formulário legítimo (que inclui o token da sessão do usuário) conseguirá passar na validação.