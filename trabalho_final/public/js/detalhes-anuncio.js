document.addEventListener('DOMContentLoaded', () => {    
    const fotoPrincipal = document.getElementById('foto-principal');
    const galeriaMiniaturas = document.getElementById('galeria-miniaturas');

    if (!galeriaMiniaturas || !fotoPrincipal) {
        return;
    }
    
    const miniaturas = galeriaMiniaturas.querySelectorAll('.miniatura');

    miniaturas.forEach(miniatura => {
        miniatura.addEventListener('click', () => {
            
            miniaturas.forEach(img => img.classList.remove('active-thumbnail'));
            
            miniatura.classList.add('active-thumbnail');
            
            fotoPrincipal.src = miniatura.src;
        });
    });

    if (miniaturas.length > 0) {
        miniaturas[0].classList.add('active-thumbnail');
    }
});