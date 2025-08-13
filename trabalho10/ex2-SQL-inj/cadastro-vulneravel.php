<?php

require "../conexaoMysql.php";
$pdo = mysqlConnect();

$nome = $_POST["nome"] ?? "";
$telefone = $_POST["telefone"] ?? "";

try {

  // NÃO FAÇA ISSO! Exemplo de código vulnerável a inj. de S-Q-L

  // O codigo pega os dados que vem do formulario e junta tudo pra montar o comando SQL 
  // O erro é confiar no que o usuario digitou, sem separar o que é comando do que é só texto

  // -----------------------------------------------------------------
  // CÓDIGO VULNERÁVEL COMENTADO
  // -----------------------------------------------------------------
  // $sql = <<<SQL
  // INSERT INTO aluno (nome, telefone)
  // VALUES ('$nome', '$telefone');
  // SQL;
  // -----------------------------------------------------------------

  // Experimente fazer o cadastro de um novo aluno preenchendo 
  // o campo telefone utilizando o texto disponibilizado pelo professor
  // nos slides de aula

  // Após rodar o exec, o php envia todo o conteudo do comando, incluindo os possiveis comando adulterados para o banco 
  // Quando o banco recebe o comando, mesmo sendo adulterado, ele executa todo o bloco recebido
  
  // -----------------------------------------------------------------
  // CÓDIGO VULNERÁVEL COMENTADO
  // -----------------------------------------------------------------
  // $pdo->exec($sql);
  // -----------------------------------------------------------------

  // Prepara o comando com placeholders no lugar dos dados, para o banco ja saber o formato correto
  $sql = <<<SQL
    INSERT INTO aluno (nome, telefone)
    VALUES (?, ?)
  SQL;

  // Envia o comando
  $stmt = $pdo->prepare($sql);

  // Envia os dados do usuário, com o banco já tratando como apenas textos, nao comandos
  $stmt->execute([$nome, $telefone]);


  header("location: mostra-alunos.php");
  exit();
} 
catch (Exception $e) {  
  exit('Falha ao cadastrar os dados: ' . $e->getMessage());
}
