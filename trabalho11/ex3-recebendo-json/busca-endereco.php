<?php
// Classe para organizar os dados do endereço
class Endereco
{
  public $rua;
  public $bairro;
  public $cidade;

  function __construct($rua, $bairro, $cidade)
  {
    $this->rua = $rua;
    $this->bairro = $bairro;
    $this->cidade = $cidade;
  }
}

$cep = $_GET['cep'] ?? '';
// Com base no CEP, cria um objeto PHP com os dados do endereço
if ($cep == '38400-100')
  $endereco = new Endereco('Av Floriano', 'Centro', 'Uberlândia');
else if ($cep == '38400-200')
  $endereco = new Endereco('Rua Tiradentes', 'Fundinho', 'Uberlândia');
else {
  $endereco = new Endereco('', '', '');
}
// Informa ao navegador que a resposta é do tipo JSON
header('Content-type: application/json');
// Converte o objeto PHP para o formato JSON e a envia como resposta
echo json_encode($endereco);
