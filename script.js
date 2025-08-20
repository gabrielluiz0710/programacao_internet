document.addEventListener('DOMContentLoaded', () => {
    
    const listItems = document.querySelectorAll('.work-list li');
    listItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 100}ms`;
    });

    const cards = document.querySelectorAll('.card');

    const throttle = (func, limit) => {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    };

    const handleMouseMove = (e) => {
        const card = e.currentTarget;
        const rect = card.getBoundingClientRect();
        const width = rect.width;
        const height = rect.height;
        const mouseX = e.clientX - rect.left;
        const mouseY = e.clientY - rect.top;
        const MAX_ROTATION = 12;

        const rotateY = (mouseX / width - 0.5) * 2 * MAX_ROTATION;
        const rotateX = (mouseY / height - 0.5) * -2 * MAX_ROTATION;
        
        card.style.setProperty('--x', `${mouseX}px`);
        card.style.setProperty('--y', `${mouseY}px`);

        card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.05, 1.05, 1.05)`;
    };

    cards.forEach(card => {
        card.addEventListener('mousemove', throttle(handleMouseMove, 16));

        card.addEventListener('mouseleave', (e) => {
            const card = e.currentTarget;
            card.style.transform = 'rotateX(0) rotateY(0) scale3d(1, 1, 1)';
        });
    });

});