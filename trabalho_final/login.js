document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');

  form.addEventListener('submit', (event) => {
    event.preventDefault();

    console.log('Login bem-sucedido, redirecionando...');
    window.location.href = 'central-user.html';
  });
});