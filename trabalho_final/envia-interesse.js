document.addEventListener("DOMContentLoaded", () => {
  const defaultQueryString =
    "titulo=Fiat+Mobi+Like&preco=R%24+68.990%2C00&ano=Ano%3A+2023&marca=Marca%3A+Fiat&modelo=Modelo%3A+Mobi&cidade=Cidade%3A+São+Paulo+-+SP&imagens=http%3A%2F%2F127.0.0.1%3A3000%2Fimages%2Ffiat_mobi.jpg%2Chttp%3A%2F%2F127.0.0.1%3A3000%2Fimages%2Ffiat_mobi_interior.jpg%2Chttp%3A%2F%2F127.0.0.1%3A3000%2Fimages%2Ffiat_mobi_traseira.jpg";

  const params = new URLSearchParams(
    window.location.search || defaultQueryString
  );

  const titulo = params.get("titulo");
  const preco = params.get("preco");
  const ano = params.get("ano");
  const marca = params.get("marca");
  const modelo = params.get("modelo");
  const cidade = params.get("cidade");
  const imagens = params.get("imagens")?.split(",").filter(Boolean);

  document.getElementById("carro-titulo").textContent =
    titulo || "Detalhes do Veículo";

  const precoEl = document.getElementById("carro-preco");
  if (precoEl && preco) {
    precoEl.textContent = preco;
  }

  function preencherCampo(id, chave, valor) {
    const elemento = document.getElementById(id);
    if (valor) {
      const strong = document.createElement("strong");
      strong.textContent = chave + ": ";
      const texto = document.createTextNode(valor.split(":")[1]?.trim() || "");

      elemento.replaceChildren(strong, texto);
    }
  }

  preencherCampo("carro-ano", "Ano", ano);
  preencherCampo("carro-marca", "Marca", marca);
  preencherCampo("carro-modelo", "Modelo", modelo);
  preencherCampo("carro-cidade", "Localização", cidade);

  const imagensContainerEl = document.getElementById("carro-imagens");

  if (imagens && imagens.length > 0) {
    let imagemAtualIndex = 0;

    imagensContainerEl.replaceChildren();

    const mainImageContainer = document.createElement("div");
    const mainImage = document.createElement("img");
    mainImage.alt = `Foto principal de ${titulo}`;

    const prevButton = document.createElement("button");
    prevButton.dataset.direction = "prev";
    prevButton.textContent = "⟨";
    prevButton.setAttribute("aria-label", "Imagem anterior");

    const nextButton = document.createElement("button");
    nextButton.dataset.direction = "next";
    nextButton.textContent = "⟩";
    nextButton.setAttribute("aria-label", "Próxima imagem");

    mainImageContainer.append(mainImage, prevButton, nextButton);

    const thumbnailsContainer = document.createElement("div");

    const mostrarImagem = (index) => {
      mainImage.src = imagens[index];
      imagemAtualIndex = index;
      thumbnailsContainer.childNodes.forEach((thumb, i) => {
        thumb.classList.toggle("active", i === index);
      });
    };

    imagens.forEach((url, index) => {
      const thumb = document.createElement("img");
      thumb.src = url;
      thumb.alt = `Miniatura ${index + 1} de ${titulo}`;
      thumb.addEventListener("click", () => mostrarImagem(index));
      thumbnailsContainer.appendChild(thumb);
    });

    prevButton.addEventListener("click", () => {
      imagemAtualIndex =
        (imagemAtualIndex - 1 + imagens.length) % imagens.length;
      mostrarImagem(imagemAtualIndex);
    });

    nextButton.addEventListener("click", () => {
      imagemAtualIndex = (imagemAtualIndex + 1) % imagens.length;
      mostrarImagem(imagemAtualIndex);
    });

    imagensContainerEl.append(mainImageContainer, thumbnailsContainer);
    mostrarImagem(0);

    if (imagens.length <= 1) {
      mainImageContainer.style.setProperty("--controls-display", "none");
    }
  } else {
    const p = document.createElement("p");
    p.textContent = "Nenhuma imagem disponível.";
    imagensContainerEl.replaceChildren(p);
  }
});
