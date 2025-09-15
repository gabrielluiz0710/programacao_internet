document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-login');
    const messageDiv = document.getElementById('form-message');

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        const formAction = form.getAttribute('action');

        submitButton.disabled = true;
        submitButton.textContent = 'Verificando...';
        messageDiv.textContent = '';
        messageDiv.className = 'message';

        fetch(formAction, {
            method: 'POST',
            body: new FormData(form)
        })
        .then(response => {
            if (!response.ok) {
                 // Tenta ler a mensagem de erro do servidor, se houver
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(result => {
            if (result.success && result.redirectUrl) {
                messageDiv.className = 'message success';
                messageDiv.textContent = 'Login bem-sucedido! Redirecionando...';
                window.location.href = result.redirectUrl;
            } else {
                // Este else pode não ser necessário se o erro for sempre tratado no catch
                throw result;
            }
        })
        .catch(error => {
            messageDiv.className = 'message error';
            messageDiv.textContent = error.message || 'Ocorreu um erro. Tente novamente.';
        })
        .finally(() => {
            // Reabilita o botão apenas se o login falhar
            if (!messageDiv.classList.contains('success')) {
                submitButton.disabled = false;
                submitButton.textContent = 'Entrar';
            }
        });
    });
});