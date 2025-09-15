document.addEventListener('DOMContentLoaded', () => {
    const formBusca = document.getElementById("form-busca");
    const marcaSelect = document.getElementById("marca");
    const modeloSelect = document.getElementById("modelo");
    const localizacaoSelect = document.getElementById("localizacao");
    const btnLimpar = document.getElementById("limpar-filtros");
    const containerDeCards = document.querySelector(".card-container");
    const mensagemSemResultados = document.querySelector(".mensagem-sem-resultados");

    function popularSelect(select, data, defaultOptionText) {
        select.innerHTML = `<option value="">${defaultOptionText}</option>`;
        data.forEach(item => {
            const value = typeof item === 'object' ? `${item.Cidade} - ${item.Estado}` : item;
            const text = typeof item === 'object' ? `${item.Cidade} - ${item.Estado}` : item;
            select.innerHTML += `<option value="${value}">${text}</option>`;
        });
        select.disabled = false;
    }
    
    async function carregarMarcas() {
        try {
            const response = await fetch('index.php?url=anuncio/marcas');
            const data = await response.json();
            if (data.success) {
                popularSelect(marcaSelect, data.marcas, 'Todas as Marcas');
            }
        } catch (error) { console.error('Erro ao carregar marcas:', error); }
    }

    async function carregarModelos(marca) {
        modeloSelect.innerHTML = '<option value="">Selecione uma marca</option>';
        modeloSelect.disabled = true;
        localizacaoSelect.innerHTML = '<option value="">Selecione um modelo</option>';
        localizacaoSelect.disabled = true;

        if (!marca) return;
        
        try {
            const response = await fetch(`index.php?url=anuncio/modelos&marca=${encodeURIComponent(marca)}`);
            const data = await response.json();
            if (data.success) {
                popularSelect(modeloSelect, data.modelos, 'Todos os Modelos');
            }
        } catch (error) { console.error('Erro ao carregar modelos:', error); }
    }

    async function carregarCidades(marca, modelo) {
        localizacaoSelect.innerHTML = '<option value="">Selecione um modelo</option>';
        localizacaoSelect.disabled = true;

        if (!marca || !modelo) return;

        try {
            const response = await fetch(`index.php?url=anuncio/cidades&marca=${encodeURIComponent(marca)}&modelo=${encodeURIComponent(modelo)}`);
            const data = await response.json();
            if (data.success) {
                popularSelect(localizacaoSelect, data.cidades, 'Todas as Cidades');
            }
        } catch (error) { console.error('Erro ao carregar cidades:', error); }
    }
    
    async function buscarAnuncios() {
        containerDeCards.innerHTML = '<p class="loading-message">Buscando anúncios...</p>';
        mensagemSemResultados.hidden = true;

        const params = new URLSearchParams({
            marca: marcaSelect.value,
            modelo: modeloSelect.value,
            localizacao: localizacaoSelect.value
        });

        try {
            const response = await fetch(`index.php?url=anuncio/buscar&${params.toString()}`);
            const data = await response.json();
            if(data.success) {
                renderizarCards(data.anuncios);
            } else {
                throw new Error(data.message || 'Falha ao buscar anúncios');
            }
        } catch (error) { 
            console.error('Erro ao buscar anúncios:', error); 
            containerDeCards.innerHTML = `<p class="error-message">${error.message}</p>`;
        }
    }

    function renderizarCards(anuncios) {
        if (anuncios.length === 0) {
            containerDeCards.innerHTML = '';
            mensagemSemResultados.hidden = false;
            return;
        }

        let cardsHtml = '';
        anuncios.forEach(anuncio => {
            const fotos = anuncio.Fotos ? anuncio.Fotos.split(',') : [];
            const precoFormatado = parseFloat(anuncio.Valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

            const imagensHtml = fotos.map((foto, index) => 
                `<img src="uploads/${foto}" alt="Foto de ${anuncio.Marca} ${anuncio.Modelo}" class="${index === 0 ? 'active' : ''}" />`
            ).join('');
            
            const botoesCarrosselHtml = fotos.length > 1 ? `
                <button class="carousel-btn prev">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"></path>
                    </svg>
                </button>
                <button class="carousel-btn next">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                        <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"></path>
                    </svg>
                </button>
            ` : '';

            cardsHtml += `
                <article class="card-item" data-id="${anuncio.Id}">
                    <div class="card-imagem-container">
                        ${imagensHtml}
                        ${botoesCarrosselHtml}
                    </div>
                    <div class="card-conteudo">
                        <h3>${anuncio.Marca} ${anuncio.Modelo}</h3>
                        <p class="preco">${precoFormatado}</p>
                        <div class="detalhes">
                            <span><strong>Ano:</strong> ${anuncio.Ano}</span>
                            <span><strong>Marca:</strong> ${anuncio.Marca}</span>
                            <span><strong>Modelo:</strong> ${anuncio.Modelo}</span>
                            <span><strong>Cidade:</strong> ${anuncio.Cidade} - ${anuncio.Estado}</span>
                        </div>
                    </div>
                    <a href="envia-interesse.php?id=${anuncio.Id}">Ver Detalhes</a>
                </article>
            `;
        });
        containerDeCards.innerHTML = cardsHtml;
    }
    
    marcaSelect.addEventListener('change', () => {
        carregarModelos(marcaSelect.value);
        buscarAnuncios();
    });

    modeloSelect.addEventListener('change', () => {
        carregarCidades(marcaSelect.value, modeloSelect.value);
        buscarAnuncios();
    });

    localizacaoSelect.addEventListener('change', () => {
        buscarAnuncios();
    });

    formBusca.addEventListener('submit', (e) => {
        e.preventDefault();
        buscarAnuncios();
    });

    btnLimpar.addEventListener('click', () => {
        formBusca.reset();
        modeloSelect.innerHTML = '<option value="">Selecione uma marca</option>';
        modeloSelect.disabled = true;
        localizacaoSelect.innerHTML = '<option value="">Selecione um modelo</option>';
        localizacaoSelect.disabled = true;
        buscarAnuncios();
    });

    containerDeCards.addEventListener('click', (event) => {
        const target = event.target;
        
        const botaoCarrossel = target.closest('.carousel-btn');
        if (botaoCarrossel) {
            const imagemContainer = botaoCarrossel.parentElement;
            const imagens = Array.from(imagemContainer.querySelectorAll('img'));
            if (imagens.length <= 1) return;

            const imagemAtiva = imagemContainer.querySelector('img.active');
            let indiceAtual = imagens.indexOf(imagemAtiva);
            
            imagemAtiva.classList.remove('active');
            
            if (botaoCarrossel.classList.contains('prev')) {
                indiceAtual = (indiceAtual - 1 + imagens.length) % imagens.length;
            } else { 
                indiceAtual = (indiceAtual + 1) % imagens.length;
            }
            
            imagens[indiceAtual].classList.add('active');
        }
    });

    carregarMarcas();
    buscarAnuncios();
});