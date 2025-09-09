document.addEventListener('DOMContentLoaded', () => {
    // --- LÓGICA DA GALERIA DE FOTOS ---
    const containerImagens = document.getElementById('carro-imagens');
    if (containerImagens) {
        const fotoPrincipal = containerImagens.querySelector('#foto-principal');
        const galeriaMiniaturas = containerImagens.querySelector('#galeria-miniaturas');
        const btnPrev = containerImagens.querySelector('.carousel-btn.prev');
        const btnNext = containerImagens.querySelector('.carousel-btn.next');
        
        if (fotoPrincipal && galeriaMiniaturas) {
            const miniaturas = galeriaMiniaturas.querySelectorAll('.miniatura');
            let indiceAtual = 0;

            function mostrarImagem(index) {
                if (index < 0 || index >= miniaturas.length) return;
                
                // Atualiza a imagem principal
                fotoPrincipal.src = miniaturas[index].src;
                
                // Atualiza a miniatura ativa
                miniaturas.forEach(img => img.classList.remove('active'));
                miniaturas[index].classList.add('active');
                
                indiceAtual = index;
            }

            // Adiciona evento de clique para cada miniatura
            miniaturas.forEach((miniatura, index) => {
                miniatura.addEventListener('click', () => mostrarImagem(index));
            });

            // Adiciona evento de clique para o botão "Anterior"
            if (btnPrev) {
                btnPrev.addEventListener('click', () => {
                    let novoIndice = (indiceAtual - 1 + miniaturas.length) % miniaturas.length;
                    mostrarImagem(novoIndice);
                });
            }

            // Adiciona evento de clique para o botão "Próximo"
            if (btnNext) {
                btnNext.addEventListener('click', () => {
                    let novoIndice = (indiceAtual + 1) % miniaturas.length;
                    mostrarImagem(novoIndice);
                });
            }
        }
    }

    // --- LÓGICA DO FORMULÁRIO DE INTERESSE ---
    const form = document.getElementById('form-interesse');
    if (form) {
        const messageDiv = document.getElementById('form-message');
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            // ... (o restante do código do formulário que você já tem e está correto) ...
            const submitButton = form.querySelector('button[type="submit"]');
            const formAction = form.getAttribute('action');

            submitButton.disabled = true;
            submitButton.textContent = 'Enviando...';
            messageDiv.className = 'message';
            messageDiv.textContent = '';

            fetch(formAction, {
                method: 'POST',
                body: new FormData(form)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    messageDiv.className = 'message success';
                    messageDiv.textContent = result.message;
                    form.reset();
                    submitButton.textContent = 'Enviado!';
                } else {
                    throw result;
                }
            })
            .catch(error => {
                messageDiv.className = 'message error';
                messageDiv.textContent = error.message || 'Ocorreu um erro.';
                submitButton.disabled = false;
                submitButton.textContent = 'Enviar Mensagem';
            });
        });
    }
});