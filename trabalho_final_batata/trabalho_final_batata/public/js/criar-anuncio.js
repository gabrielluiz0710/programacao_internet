document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-anuncio');
    // Verificação inicial
    if (!form) {
        console.error("DEBUG: Formulário #form-anuncio não encontrado!");
        return;
    }
    
    const messageDiv = document.getElementById('form-message');
    if (!messageDiv) {
        console.error("DEBUG: Div de mensagens #form-message não encontrada!");
        return;
    }

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        console.log("DEBUG: Formulário enviado, prevenindo recarregamento da página.");

        const submitButton = form.querySelector('button[type="submit"]');
        const formAction = form.getAttribute('action');
        
        const formData = new FormData(form);

        submitButton.disabled = true;
        submitButton.textContent = 'Enviando anúncio...';
        messageDiv.textContent = '';
        messageDiv.className = 'message';

        console.log("DEBUG: Enviando requisição FETCH para:", formAction);

        fetch(formAction, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log("DEBUG: Recebi uma resposta do servidor. Status:", response.status);
            if (!response.ok) {
                console.error("DEBUG: Resposta NÃO foi OK. Tentando ler JSON de erro.");
                return response.json().then(err => { throw err; });
            }
            console.log("DEBUG: Resposta OK. Lendo JSON de sucesso.");
            return response.json();
        })
        .then(result => {
            console.log("DEBUG: JSON de sucesso processado:", result);
            if (result.success && result.redirectUrl) {
                messageDiv.className = 'message success';
                messageDiv.textContent = result.message + ' Redirecionando...';
                window.location.href = result.redirectUrl; 
            } else {
                // Se success for false, joga para o catch
                throw result;
            }
        })
        .catch(error => {
            console.error("DEBUG: Ocorreu um erro no bloco CATCH:", error);
            messageDiv.className = 'message error';
            messageDiv.textContent = error.message || 'Ocorreu um erro ao criar o anúncio.';
            submitButton.disabled = false;
            submitButton.textContent = 'Criar Anúncio';
        });
    });
});