document.addEventListener('DOMContentLoaded', () => {
    
    // Animação de entrada dos itens da lista
    const listItems = document.querySelectorAll('.work-list li');
    listItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 100}ms`;
    });

    // Lógica otimizada para os cards 3D
    const cards = document.querySelectorAll('.card');
    
    // Checa se o usuário prefere movimento reduzido ou se é um dispositivo touch
    const motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
    const isTouchDevice = 'ontouchstart' in window;

    // Só executa a animação complexa se o usuário não tiver restrições e não for touch
    if (!motionQuery.matches && !isTouchDevice) {
        
        cards.forEach(card => {
            let rect; // Variável para guardar as dimensões do card

            // 1. Quando o mouse ENTRA, pegamos as dimensões UMA VEZ.
            card.addEventListener('mouseenter', () => {
                rect = card.getBoundingClientRect();
            });

            // 2. Quando o mouse se MOVE, usamos as dimensões já guardadas.
            card.addEventListener('mousemove', (e) => {
                if (!rect) return; // Se não tivermos as dimensões, não faz nada

                const mouseX = e.clientX - rect.left;
                const mouseY = e.clientY - rect.top;
                const MAX_ROTATION = 12;

                // Calcula a rotação com base na posição do mouse dentro do card
                const rotateY = (mouseX / rect.width - 0.5) * 2 * MAX_ROTATION;
                const rotateX = (mouseY / rect.height - 0.5) * -2 * MAX_ROTATION;
                
                // Aplica a transformação e as variáveis para o brilho
                card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.05, 1.05, 1.05)`;
                card.style.setProperty('--x', `${mouseX}px`);
                card.style.setProperty('--y', `${mouseY}px`);
            });

            // 3. Quando o mouse SAI, resetamos tudo.
            card.addEventListener('mouseleave', () => {
                rect = null; // Limpa as dimensões
                card.style.transform = 'rotateX(0) rotateY(0) scale3d(1, 1, 1)';
            });
        });
    }
});