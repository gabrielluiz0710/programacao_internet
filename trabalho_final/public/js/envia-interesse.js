document.addEventListener('DOMContentLoaded', () => {
    // --- LÓGICA DA GALERIA DE FOTOS ---
    const fotoPrincipal = document.getElementById('foto-principal');
    const galeriaMiniaturas = document.getElementById('galeria-miniaturas');

    if (galeriaMiniaturas && fotoPrincipal) {
        const miniaturas = galeriaMiniaturas.querySelectorAll('.miniatura');
        miniaturas.forEach(miniatura => {
            miniatura.addEventListener('click', () => {
                fotoPrincipal.src = miniatura.src;
                miniaturas.forEach(img => img.classList.remove('active'));
                miniatura.classList.add('active');
            });
        });
        if (miniaturas.length > 0) {
            miniaturas[0].classList.add('active');
        }
    }

    // --- LÓGICA DO FORMULÁRIO DE INTERESSE ---
    const form = document.getElementById('form-interesse');
    const messageDiv = document.getElementById('form-message');

    if (form) {
        form.addEventListener('submit', (event) => {
            event.preventDefault();

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
                    form.reset(); // Limpa o formulário
                    // Mantém o botão desabilitado para evitar múltiplos envios
                    submitButton.textContent = 'Enviado!';
                } else {
                    throw result;
                }
            })
            .catch(error => {
                messageDiv.className = 'message error';
                messageDiv.textContent = error.message || 'Ocorreu um erro.';
                // Reabilita o botão em caso de erro
                submitButton.disabled = false;
                submitButton.textContent = 'Enviar Mensagem';
            });
        });
    }
});