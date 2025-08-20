<?php
// Pega o valor do CEP que foi passado na URL, caso não exista usa uma string vazia
$cep = $_GET['cep'] ?? '';

// Se o valor for 38400-100, retorna Uberlândia
if ($cep == '38400-100')
  echo 'Uberlândia';
// Se não for, caso seja 38700-000, retorna Patos de Minas
else if ($cep == '38700-000')
  echo 'Patos de Minas';
// Caso contrário, retorna erro 404 e mensagem de erro
else {
  http_response_code(404);
  echo "$cep is not available";
}