document.addEventListener('DOMContentLoaded', () => {
    const containerDeCards = document.querySelector('.card-container');

    if (!containerDeCards) {
        console.error("Container de cards não encontrado!");
        return;
    }

    // Função para buscar os anúncios e renderizá-los
    async function carregarAnuncios() {
        try {
            const response = await fetch('index.php?url=anuncio/listarPorUsuario');
            if (!response.ok) {
                throw new Error('Falha ao carregar os anúncios.');
            }
            const data = await response.json();

            if (data.success) {
                renderizarCards(data.anuncios);
            } else {
                containerDeCards.innerHTML = `<p class="error-message">${data.message}</p>`;
            }

        } catch (error) {
            containerDeCards.innerHTML = `<p class="error-message">${error.message}</p>`;
        }
    }

    // Função para criar o HTML de cada card
    function renderizarCards(anuncios) {
        if (anuncios.length === 0) {
            containerDeCards.innerHTML = `<p class="info-message">Você ainda não cadastrou nenhum anúncio. <a href="criar-anuncio.php">Crie o seu primeiro!</a></p>`;
            return;
        }

        let cardsHtml = '';
        anuncios.forEach(anuncio => {
            const fotos = anuncio.Fotos ? anuncio.Fotos.split(',') : [];
            const primeiraFoto = fotos.length > 0 ? `uploads/${fotos[0]}` : 'images/placeholder.png'; // Imagem padrão

            // Formata o preço para o padrão brasileiro
            const precoFormatado = parseFloat(anuncio.Valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

            // Gera o HTML das imagens para o carrossel
            const imagensHtml = fotos.map((foto, index) => 
                `<img src="uploads/${foto}" alt="Foto de ${anuncio.Marca} ${anuncio.Modelo}" class="${index === 0 ? 'active' : ''}" />`
            ).join('');

            cardsHtml += `
                <article class="card-item">
                    <div class="card-imagem-container">
                        ${imagensHtml}
                        ${fotos.length > 1 ? `
                            <button class="carousel-btn prev">&lt;</button>
                            <button class="carousel-btn next">&gt;</button>
                        ` : ''}
                    </div>
                    <div class="card-conteudo">
                        <h3>${anuncio.Marca} ${anuncio.Modelo}</h3>
                        <p class="preco">${precoFormatado}</p>
                        <div class="detalhes">
                            <span><strong>Ano:</strong> ${anuncio.Ano}</span>
                            <span><strong>KM:</strong> ${parseInt(anuncio.Quilometragem).toLocaleString('pt-BR')}</span>
                            <span><strong>Cidade:</strong> ${anuncio.Cidade} - ${anuncio.Estado}</span>
                        </div>
                    </div>
                    <a href="#" data-action="detalhes"> 
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20"
                                height="20">
                                <path
                                    d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                        </svg>
                        Visualização Detalhada
                    </a>
                    <a href="#" data-action="interesse">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20"
                                height="20">
                                <path
                                    d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0l-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z" />
                            </svg>
                            Visualizar Interesse
                        </a>
                        <a href="#" data-action="remover">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20"
                                height="20">
                                <path
                                    d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                            </svg>
                            Remover Anúncio
                        </a>
                </article>
            `;
        });

        containerDeCards.innerHTML = cardsHtml;
    }

    // O manipulador de eventos para o carrossel e ações (seu código original adaptado)
    containerDeCards.addEventListener('click', (event) => {
        const target = event.target;
        
        // Lógica do Carrossel
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

        // Lógica dos links de ação (Remover, etc.)
        const actionLink = target.closest('a[data-action]');
        if (actionLink) {
            event.preventDefault();
            const action = actionLink.dataset.action;
            const card = actionLink.closest('.card-item');
            
            if (action === 'remover') {
                if (confirm('Tem certeza que deseja remover este anúncio?')) {
                    // Aqui você faria uma requisição fetch para remover do banco de dados
                    console.log('Remover anúncio ID:', card.dataset.id); // Futuramente, adicione data-id="${anuncio.Id}" ao article
                    card.remove();
                }
            }
            // Adicionar lógica para outros botões se necessário
        }
    });

    // Inicia o processo ao carregar a página
    carregarAnuncios();
});