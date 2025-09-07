document.addEventListener('DOMContentLoaded', () => {

  const defaultQueryString =
    "titulo=Fiat+Mobi+Like&preco=R%24+68.990%2C00&ano=Ano%3A+2023&marca=Marca%3A+Fiat&modelo=Modelo%3A+Mobi&cidade=Cidade%3A+São+Paulo+-+SP&imagens=http%3A%2F%2F127.0.0.1%3A3000%2Fimages%2Ffiat_mobi.jpg%2Chttp%3A%2F%2F127.0.0.1%3A3000%2Fimages%2Ffiat_mobi_interior.jpg%2Chttp%3A%2F%2F127.0.0.1%3A3000%2Fimages%2Ffiat_mobi_traseira.jpg";

  const params = new URLSearchParams(
    window.location.search || defaultQueryString
  );

  const titulo = params.get('titulo') || "Anúncio não informado";
  const preco = params.get('preco') || "Valor não informado";
  const marca = params.get('marca')?.split(':')[1]?.trim() || "N/A";
  const modelo = params.get('modelo')?.split(':')[1]?.trim() || "N/A";
  const ano = params.get('ano')?.split(':')[1]?.trim() || "N/A";

  document.getElementById('titulo-anuncio').textContent = `Interesses no Anúncio: ${titulo}`;

  document.getElementById('anuncio-marca').textContent = `Marca: ${marca}`;
  document.getElementById('anuncio-modelo').textContent = `Modelo: ${modelo}`;
  document.getElementById('anuncio-ano').textContent = `Ano: ${ano}`;
  document.getElementById('anuncio-preco').textContent = `Valor: ${preco}`;

});