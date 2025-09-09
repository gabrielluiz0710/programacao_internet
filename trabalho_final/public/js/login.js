document.addEventListener('DOMContentLoaded', () => {
    // Seletores dos elementos do DOM
    const formBusca = document.getElementById("form-busca");
    const marcaSelect = document.getElementById("marca");
    const modeloSelect = document.getElementById("modelo");
    const localizacaoSelect = document.getElementById("localizacao");
    const btnLimpar = document.getElementById("limpar-filtros");
    const containerDeCards = document.querySelector(".card-container");
    const mensagemSemResultados = document.querySelector(".mensagem-sem-resultados");

    // Função genérica para popular um <select>
    function popularSelect(select, data, defaultOptionText) {
        select.innerHTML = `<option value="">${defaultOptionText}</option>`;
        data.forEach(item => {
            const value = typeof item === 'object' ? `${item.Cidade} - ${item.Estado}` : item;
            const text = typeof item === 'object' ? `${item.Cidade} - ${item.Estado}` : item;
            select.innerHTML += `<option value="${value}">${text}</option>`;
        });
        select.disabled = false;
    }
    
    // Carrega as marcas iniciais
    async function carregarMarcas() {
        try {
            const response = await fetch('index.php?url=anuncio/marcas');
            const data = await response.json();
            if (data.success) {
                popularSelect(marcaSelect, data.marcas, 'Todas as Marcas');
            }
        } catch (error) { console.error('Erro ao carregar marcas:', error); }
    }

    // Carrega os modelos baseados na marca
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

    // Carrega as cidades baseadas na marca e modelo
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
    
    // Busca e renderiza os anúncios
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
            }
        } catch (error) { console.error('Erro ao buscar anúncios:', error); }
    }

    // Lógica de renderização dos cards (similar à de meus-anuncios.js)
    function renderizarCards(anuncios) {
        if (anuncios.length === 0) {
            containerDeCards.innerHTML = '';
            mensagemSemResultados.hidden = false;
            return;
        }
        // ... (Cole a função renderizarCards de meus-anuncios.js aqui, ajustando o link para 'envia-interesse.php?id=...' se necessário)
        // Por simplicidade, segue uma versão básica:
        let cardsHtml = '';
        anuncios.forEach(anuncio => {
            const precoFormatado = parseFloat(anuncio.Valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            cardsHtml += `
                <article class="card-item">
                    <h3>${anuncio.Marca} ${anuncio.Modelo}</h3>
                    <p class="preco">${precoFormatado}</p>
                    <div class="detalhes">
                        <span><strong>Ano:</strong> ${anuncio.Ano}</span>
                        <span><strong>Cidade:</strong> ${anuncio.Cidade} - ${anuncio.Estado}</span>
                    </div>
                     <a href="envia-interesse.php?id=${anuncio.Id}">Ver Detalhes</a>
                </article>
            `;
        });
        containerDeCards.innerHTML = cardsHtml;
    }


    // Event Listeners
    marcaSelect.addEventListener('change', () => {
        carregarModelos(marcaSelect.value);
        buscarAnuncios();
    });

    modeloSelect.addEventListener('change', () => {
        carregarCidades(marcaSelect.value, modeloSelect.value);
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

    // Carga inicial
    carregarMarcas();
    buscarAnuncios();
});