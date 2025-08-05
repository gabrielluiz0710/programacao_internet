document.addEventListener("DOMContentLoaded", () => {
  const modelosPorMarca = {
    fiat: [
      { value: "mobi", text: "Mobi" },
      { value: "strada", text: "Strada" },
    ],
    volkswagen: [
      { value: "polo", text: "Polo" },
      { value: "gol", text: "Gol" },
    ],
    chevrolet: [{ value: "onix", text: "Onix" }],
    honda: [
      { value: "hr-v", text: "HR-V" },
      { value: "civic", text: "Civic" },
    ],
    toyota: [{ value: "corolla", text: "Corolla" }],
    ford: [{ value: "ranger", text: "Ranger" }],
  };

  const marcaPorModelo = {
    mobi: "fiat",
    strada: "fiat",
    polo: "volkswagen",
    gol: "volkswagen",
    onix: "chevrolet",
    "hr-v": "honda",
    civic: "honda",
    corolla: "toyota",
    ranger: "ford",
  };

  const formBusca = document.querySelector(".painel-pesquisa form");
  const marcaSelect = document.getElementById("marca");
  const modeloSelect = document.getElementById("modelo");
  const localizacaoSelect = document.getElementById("localizacao");
  const btnLimpar = document.getElementById("limpar-filtros");
  const mensagem = document.querySelector(".mensagem-sem-resultados");
  const containerDeCards = document.querySelector(".card-container");

  const todosOsCards = document.querySelectorAll(".card-item");


  const todosOsModelosOptions = Array.from(modeloSelect.options);

  function popularModelos(marca) {
    modeloSelect.replaceChildren();

    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Todos os Modelos";
    modeloSelect.appendChild(defaultOption);

    if (marca && modelosPorMarca[marca]) {
      modelosPorMarca[marca].forEach((modelo) => {
        const option = document.createElement("option");
        option.value = modelo.value;
        option.textContent = modelo.text;
        modeloSelect.appendChild(option);
      });
    } else {
      todosOsModelosOptions.forEach((option, index) => {
        if (index > 0) {
          modeloSelect.appendChild(option.cloneNode(true));
        }
      });
    }
  }

  function aplicarFiltros() {
    const marcaFiltro = marcaSelect.value;
    const modeloFiltro = modeloSelect.value;
    const localizacaoFiltro = localizacaoSelect.value;

    let cardsVisiveis = 0;

    todosOsCards.forEach((card) => {
        const detalhesDiv = card.querySelector(".detalhes");
        if (!detalhesDiv) {
            card.style.display = "none";
            return;
        }

        const marcaCard = detalhesDiv.querySelector("span:nth-child(2)").textContent.split(":")[1].trim().toLowerCase();
        const modeloCard = detalhesDiv.querySelector("span:nth-child(3)").textContent.split(":")[1].trim().toLowerCase();
        
        const localizacaoCard = detalhesDiv.querySelector("span:nth-child(4)").textContent.split(":")[1].trim().toLowerCase();

        const marcaValida = !marcaFiltro || marcaCard.includes(marcaFiltro);
        const modeloValido = !modeloFiltro || modeloCard.includes(modeloFiltro);
        
        const localizacaoValida = !localizacaoFiltro || localizacaoCard === localizacaoFiltro;

        if (marcaValida && modeloValido && localizacaoValida) {
            card.style.display = "flex";
            cardsVisiveis++;
        } else {
            card.style.display = "none";
        }
    });

    mensagem.hidden = cardsVisiveis > 0;
}

  formBusca.addEventListener("submit", (event) => {
    event.preventDefault();
    aplicarFiltros();
  });

  btnLimpar.addEventListener("click", () => {
    formBusca.reset();
    popularModelos("");
    aplicarFiltros();
  });

  marcaSelect.addEventListener("change", () => {
    const marcaSelecionada = marcaSelect.value;
    popularModelos(marcaSelecionada);
  });

  modeloSelect.addEventListener("change", () => {
    const modeloSelecionado = modeloSelect.value;
    if (modeloSelecionado && marcaPorModelo[modeloSelecionado]) {
      const marcaCorrespondente = marcaPorModelo[modeloSelecionado];
      if (marcaSelect.value !== marcaCorrespondente) {
        marcaSelect.value = marcaCorrespondente;
        popularModelos(marcaCorrespondente);
        modeloSelect.value = modeloSelecionado;
      }
    }
  });

  containerDeCards.addEventListener("click", (event) => {
    const botaoCarrossel = event.target.closest(".card-imagem-container button");
    const linkDetalhes = event.target.closest("a");

    if (botaoCarrossel) {
      event.preventDefault();
      const imagemContainer = botaoCarrossel.parentElement;
      const imagens = Array.from(imagemContainer.querySelectorAll("img"));
      if (imagens.length <= 1) return;
      
      const imagemAtiva = imagemContainer.querySelector("img.active");
      let indiceAtual = imagens.indexOf(imagemAtiva);
      imagemAtiva.classList.remove("active");

      const botoes = Array.from(imagemContainer.querySelectorAll("button"));
      if (botaoCarrossel === botoes[0]) {
        indiceAtual = (indiceAtual - 1 + imagens.length) % imagens.length;
      } else {
        indiceAtual = (indiceAtual + 1) % imagens.length;
      }
      imagens[indiceAtual].classList.add("active");
    } 
    else if (linkDetalhes) {
      event.preventDefault();
      const card = linkDetalhes.closest(".card-item");
      const titulo = card.querySelector("h3").textContent;
      const ano = card.querySelector(".detalhes span:nth-child(1)").textContent;
      const marca = card.querySelector(".detalhes span:nth-child(2)").textContent;
      const modelo = card.querySelector(".detalhes span:nth-child(3)").textContent;
      const cidade = card.querySelector(".detalhes span:nth-child(4)").textContent;
      const imagensNodeList = card.querySelectorAll("img");
      const imagensArray = Array.from(imagensNodeList).map((img) => img.src);
      const preco = card.querySelector(".preco").textContent;

      const params = new URLSearchParams();
      params.append("titulo", titulo);
      params.append("preco", preco); 
      params.append("ano", ano);
      params.append("marca", marca);
      params.append("modelo", modelo);
      params.append("cidade", cidade);
      params.append("imagens", imagensArray.join(","));
      
      window.location.href = `envia-interesse.html?${params.toString()}`;
    }
  });
});
