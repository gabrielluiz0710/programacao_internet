document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-anuncio');
    const messageDiv = document.getElementById('form-message');

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        const formAction = form.getAttribute('action');
        
        // FormData é essencial para enviar arquivos via fetch
        const formData = new FormData(form);

        submitButton.disabled = true;
        submitButton.textContent = 'Enviando anúncio...';
        messageDiv.textContent = '';
        messageDiv.className = 'message';

        fetch(formAction, {
            method: 'POST',
            body: formData // Não defina o 'Content-Type', o navegador faz isso por você
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(result => {
            if (result.success && result.redirectUrl) {
                messageDiv.className = 'message success';
                messageDiv.textContent = result.message + ' Redirecionando...';
                // Renomeie 'meus-anuncios.html' para '.php' se for uma página protegida também
                window.location.href = result.redirectUrl; 
            } else {
                throw result;
            }
        })
        .catch(error => {
            messageDiv.className = 'message error';
            messageDiv.textContent = error.message || 'Ocorreu um erro ao criar o anúncio.';
            // Reabilita o botão em caso de erro
            submitButton.disabled = false;
            submitButton.textContent = 'Criar Anúncio';
        });
    });
});