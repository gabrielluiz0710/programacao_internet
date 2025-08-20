<?php
// Classe para estruturar a resposta que será enviada como JSON
class LoginResult
{
  public $isAuthorized;
  public $newLocation;

  function __construct($isAuthorized, $newLocation)
  {
    $this->isAuthorized = $isAuthorized;
    $this->newLocation = $newLocation;
  }
}
// Resgata os dados enviados via POST pelo JavaScript
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Validação simplificada para fins didáticos. Não faça isso!
if ($email == 'fulano@mail.com' && $senha == '123456')
  // Se as credenciais estiverem corretas, cria um objeto de resposta
  // indicando sucesso e a página para onde redirecionar
  $loginResult = new LoginResult(true, 'home.html');
else
  // Se estiverem incorretas, cria um objeto indicando falha
  $loginResult = new LoginResult(false, '');
// Define o cabeçalho da resposta para indicar que o conteúdo é JSON
header('Content-type: application/json');
// Converte o objeto PHP para uma string JSON e a envia de volta ao navegador
echo json_encode($loginResult);