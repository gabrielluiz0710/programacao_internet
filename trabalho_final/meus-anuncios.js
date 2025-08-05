document.addEventListener('DOMContentLoaded', () => {
    const containerDeCards = document.querySelector('.card-container');

    if (!containerDeCards) {
        console.error("Container de cards não encontrado!");
        return;
    }

    containerDeCards.addEventListener('click', (event) => {
        const botaoCarrossel = event.target.closest('.card-imagem-container button');
        const actionLink = event.target.closest('a[data-action]');

        if (botaoCarrossel) {
            event.preventDefault(); 

            const imagemContainer = botaoCarrossel.parentElement;
            const imagens = Array.from(imagemContainer.querySelectorAll('img'));
            
            if (imagens.length <= 1) return; 

            const imagemAtiva = imagemContainer.querySelector('img.active');
            let indiceAtual = imagens.indexOf(imagemAtiva);
            imagemAtiva.classList.remove('active');

            const botoes = Array.from(imagemContainer.querySelectorAll('button'));
            if (botaoCarrossel === botoes[0]) {
                indiceAtual = (indiceAtual - 1 + imagens.length) % imagens.length;
            } else {
                indiceAtual = (indiceAtual + 1) % imagens.length;
            }

            imagens[indiceAtual].classList.add('active');
        } 
        else if (actionLink) {
            event.preventDefault();
            
            const card = actionLink.closest('.card-item');
            const action = actionLink.dataset.action;

            if (action === 'remover') {
                if (confirm('Tem certeza que deseja remover este anúncio?')) {
                    card.style.transition = 'opacity 0.5s ease';
                    card.style.opacity = '0';
                    setTimeout(() => card.remove(), 500);
                }
                return;
            }
            
            const titulo = card.querySelector('h3').textContent;
            const preco = card.querySelector('.preco').textContent;
            const ano = card.querySelector('.detalhes span:nth-child(1)').textContent;
            const marca = card.querySelector('.detalhes span:nth-child(2)').textContent;
            const modelo = card.querySelector('.detalhes span:nth-child(3)').textContent;
            const cidade = card.querySelector('.detalhes span:nth-child(4)').textContent;
            const urlsImagens = Array.from(card.querySelectorAll('.card-imagem-container img'))
                                    .map(img => img.src)
                                    .join(',');

            const params = new URLSearchParams();
            params.append('titulo', titulo);
            params.append('preco', preco);
            params.append('ano', ano);
            params.append('marca', marca);
            params.append('modelo', modelo);
            params.append('cidade', cidade);
            params.append('imagens', urlsImagens);

            if (action === 'detalhes') {
                window.location.href = `detalhes-anuncio.html?${params.toString()}`;
            } else if (action === 'interesse') {
                window.location.href = `interesse-anuncio.html?${params.toString()}`;
            }
        }
    });
});