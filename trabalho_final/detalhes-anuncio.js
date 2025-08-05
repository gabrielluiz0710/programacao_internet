document.addEventListener('DOMContentLoaded', () => {
    const defaultQueryString =
    "titulo=Fiat+Mobi+Like&preco=R%24+68.990%2C00&ano=Ano%3A+2023&marca=Marca%3A+Fiat&modelo=Modelo%3A+Mobi&cidade=Cidade%3A+São+Paulo+-+SP&imagens=http%3A%2F%2F127.0.0.1%3A3000%2Fimages%2Ffiat_mobi.jpg%2Chttp%3A%2F%2F127.0.0.1%3A3000%2Fimages%2Ffiat_mobi_interior.jpg%2Chttp%3A%2F%2F127.0.0.1%3A3000%2Fimages%2Ffiat_mobi_traseira.jpg";

  const params = new URLSearchParams(
    window.location.search || defaultQueryString
  );

    const titulo = params.get('titulo') || "Título não informado";
    const preco = params.get('preco') || "Preço não informado";
    const ano = params.get('ano')?.split(':')[1]?.trim() || "Não informado";
    const marca = params.get('marca')?.split(':')[1]?.trim() || "Não informada";
    const modelo = params.get('modelo')?.split(':')[1]?.trim() || "Não informado";
    const cidade = params.get('cidade')?.split(':')[1]?.trim() || "Não informada";
    const imagens = params.get('imagens')?.split(',') || [];

    document.getElementById('anuncio-titulo').textContent = titulo;
    document.getElementById('anuncio-preco').textContent = preco;
    document.getElementById('anuncio-marca').textContent = marca;
    document.getElementById('anuncio-modelo').textContent = modelo;
    document.getElementById('anuncio-ano').textContent = ano;
    document.getElementById('anuncio-localizacao').textContent = cidade;
    
    const linkInteresse = document.getElementById('link-interesse');
    if (linkInteresse) {
        linkInteresse.href = `interesse-anuncio.html?${params.toString()}`;
    }

    const fotoPrincipal = document.getElementById('foto-principal');
    const galeriaMiniaturas = document.getElementById('galeria-miniaturas');

    if (imagens.length > 0 && imagens[0]) {
        fotoPrincipal.src = imagens[0];
        fotoPrincipal.alt = `Foto principal de ${titulo}`;

        galeriaMiniaturas.replaceChildren(); 

        imagens.forEach((url, index) => {
            const miniatura = document.createElement('img');
            miniatura.src = url;
            miniatura.alt = `Foto de ${titulo} (${index + 1})`;
            
            if (index === 0) {
                miniatura.classList.add('active-thumbnail');
            }

            miniatura.addEventListener('click', () => {
                galeriaMiniaturas.querySelectorAll('img').forEach(img => img.classList.remove('active-thumbnail'));
                
                miniatura.classList.add('active-thumbnail');

                fotoPrincipal.src = url;
            });

            galeriaMiniaturas.appendChild(miniatura);
        });
    } else {
        fotoPrincipal.style.display = 'none';
    }
});