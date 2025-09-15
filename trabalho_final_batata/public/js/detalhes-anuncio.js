document.addEventListener('DOMContentLoaded', () => {
    // Seleciona a imagem principal e o contêiner das miniaturas.
    const fotoPrincipal = document.getElementById('foto-principal');
    const galeriaMiniaturas = document.getElementById('galeria-miniaturas');

    // Se não houver uma galeria na página, não faz nada.
    if (!galeriaMiniaturas || !fotoPrincipal) {
        return;
    }
    
    // Pega todas as imagens de miniatura que foram geradas pelo PHP.
    const miniaturas = galeriaMiniaturas.querySelectorAll('.miniatura');

    // Adiciona um "ouvinte de evento" de clique para cada miniatura.
    miniaturas.forEach(miniatura => {
        miniatura.addEventListener('click', () => {
            
            // 1. Remove a borda de destaque de todas as outras miniaturas.
            miniaturas.forEach(img => img.classList.remove('active-thumbnail'));
            
            // 2. Adiciona a borda de destaque apenas na miniatura que foi clicada.
            miniatura.classList.add('active-thumbnail');
            
            // 3. Atualiza o 'src' (a fonte da imagem) da foto principal
            //    com o mesmo 'src' da miniatura clicada.
            fotoPrincipal.src = miniatura.src;
        });
    });

    // Marca a primeira miniatura como ativa ao carregar a página
    if (miniaturas.length > 0) {
        miniaturas[0].classList.add('active-thumbnail');
    }
});