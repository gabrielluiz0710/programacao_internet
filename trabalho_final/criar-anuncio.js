document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-anuncio');

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        window.location.href = 'meus-anuncios.html';
    });
});