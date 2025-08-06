document.addEventListener("DOMContentLoaded", () => {
    window.scrollTo(0, 0);

    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');
    const tabButtons = document.querySelectorAll('.tab-button');
    const productCards = document.querySelectorAll('.product-card');
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const saboresSection = document.querySelector('#sabores');

    const categoryDisplayNames = {};
    tabButtons.forEach(button => {
        const categoryKey = button.dataset.category;
        const displayName = button.textContent;
        categoryDisplayNames[categoryKey] = displayName;
    });

    const wrapper = document.querySelector('.custom-select-wrapper');
    if (wrapper) {
        const trigger = document.createElement('div');
        trigger.className = 'custom-select-trigger';
        
        const options = document.createElement('div');
        options.className = 'custom-options';
        
        const container = document.createElement('div');
        container.className = 'custom-select-container';

        Array.from(categorySelect.options).forEach(option => {
            const customOption = document.createElement('div');
            customOption.className = 'custom-option';
            customOption.textContent = option.textContent;
            customOption.dataset.value = option.value;
            options.appendChild(customOption);
        });

        container.appendChild(trigger);
        container.appendChild(options);
        wrapper.appendChild(container);

        function updateTriggerText() {
            trigger.textContent = categorySelect.options[categorySelect.selectedIndex].textContent;
        }

        trigger.addEventListener('click', () => {
            container.classList.toggle('open');
        });

        options.querySelectorAll('.custom-option').forEach(option => {
            option.addEventListener('click', () => {
                options.querySelectorAll('.custom-option').forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');

                categorySelect.value = option.dataset.value;
                updateTriggerText();
                container.classList.remove('open');
                
                categorySelect.dispatchEvent(new Event('change'));
            });
        });

        window.addEventListener('click', (e) => {
            if (!container.contains(e.target)) {
                container.classList.remove('open');
            }
        });
        
        updateTriggerText(); 
    }

    function filterAndDisplayProducts() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedCategory = categorySelect.value;
        let visibleProductsCount = 0;

        tabButtons.forEach(btn => {
            if (btn.dataset.category === selectedCategory) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        productCards.forEach(card => {
            const productName = card.querySelector('h3').textContent.toLowerCase();
            const productCategory = Array.from(card.classList).find(cls => cls !== 'product-card' && cls !== 'visible');

            let categoryElement = card.querySelector('.product-card-category');
            if (!categoryElement) {
                categoryElement = document.createElement('p');
                categoryElement.className = 'product-card-category';
                card.querySelector('h3').insertAdjacentElement('afterend', categoryElement);
            }

            const categoryMatch = selectedCategory === 'all' || productCategory === selectedCategory;
            const searchMatch = productName.includes(searchTerm);

            if (categoryMatch && searchMatch) {
                card.style.display = 'block';
                visibleProductsCount++;

                if (selectedCategory === 'all') {
                    const formattedCategory = categoryDisplayNames[productCategory] || productCategory;
                    categoryElement.textContent = formattedCategory;
                    categoryElement.style.display = 'block';
                } else {
                    categoryElement.style.display = 'none';
                }

            } else {
                card.style.display = 'none';
                categoryElement.style.display = 'none';
            }
        });

        if (noResultsMessage) {
            noResultsMessage.style.display = visibleProductsCount === 0 ? 'block' : 'none';
        }
    }

    window.addEventListener('scroll', () => {
        if (saboresSection && window.scrollY > saboresSection.offsetTop) {
            scrollToTopBtn.classList.add('show');
        } else {
            scrollToTopBtn.classList.remove('show');
        }
    });

    searchInput.addEventListener('input', filterAndDisplayProducts);
    categorySelect.addEventListener('change', filterAndDisplayProducts);

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const category = button.dataset.category;
            categorySelect.value = category;
            categorySelect.dispatchEvent(new Event('change'));
            document.querySelector('.custom-select-trigger').textContent = categoryDisplayNames[category] || "Todas as Categorias";
        });
    });

    scrollToTopBtn.addEventListener('click', (event) => {
        event.preventDefault();
        saboresSection.scrollIntoView({ behavior: 'smooth' });
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
            }
        });
    }, { threshold: 0.1 });
    
    productCards.forEach(card => observer.observe(card));
    
    filterAndDisplayProducts();

    const hero = document.querySelector(".hero");
    if (hero) {
        const heroContent = document.querySelector(".hero-content");
        const heroIceCream = document.querySelector(".hero-ice-cream");

        hero.addEventListener("mousemove", (e) => {
            const x = e.clientX;
            const y = e.clientY;

            const moveX = (x - window.innerWidth / 2) / 40;
            const moveY = (y - window.innerHeight / 2) / 40;

            if (heroContent) {
                heroContent.style.transform = `translate(${moveX}px, ${moveY}px)`;
            }
            if (heroIceCream) {
                heroIceCream.style.transform = `translate(calc(-50% + ${-moveX}px), calc(-50% + ${-moveY}px))`;
            }
        });
    }

    const coresSabor = {
        // Sorvetes
        abacaxi: "#fce181",
        "abacaxi ao vinho": "#eeb1e6ff",
        prestígio: "#f2d6c4ff",
        ameixa: "#b36f2fff",
        amendoim: "#d2a679",
        banana: "#fff6a2ff",
        "blue ice": "#3498db",
        bombom: "#c48048ff",
        chiclete: "#ff7edb",
        chocolate: "#7b3f00",
        "chocolate branco": "#fcffd2ff",
        chocomenta: "#98d9b6",
        coco: "#dfdfdfff",
        "coco queimado": "#a1724b",
        creme: "#ffd986ff",
        cupuaçu: "#f1eadbff",
        "doce de leite": "#c68642",
        flocos: "#f5f5dc",
        "frutas vermelhas": "#e08393ff",
        "iogurte com maracujá": "#e6d084ff",
        "leite condensado": "#fffacaff",
        "leite ninho": "#fff5e6ff",
        "leite ninho trufado": "#fff5e6ff",
        limão: "#d8e6a2ff",
        maracujá: "#ffc40c",
        "milho verde": "#fbec5d",
        "morango com iogurte": "#fc5a8d",
        "passas ao rum": "#dbcd4fff",
        "romeu e julieta": "#fddde6",
        sensação: "#fe758f",
        "torta alemã": "#eaddca",
        uva: "#6f2da8",
        // Picolés (adicione outros aqui se precisar)
        abacate: "#8db600",
        cajá: "#ffbf00",
        kiwi: "#8ee53f",
        goiaba: "#f88379",
        "limão suíço": "#c9cc99",
        churros: "#f4a460",
        coalhada: "#fff8dc",
        nata: "#fff5e6",
        queijo: "#fffacd",
        "uva ao leite": "#b19cd9",
        acerola: "#ff0033",
        "cajá-manga": "#ffba61ff",
        groselha: "#ff1cae",
        laranja: "#ffa500",
        "pinta língua": "#2c47dfff",
        tamarindo: "#ff7518",
    };


    const sorvetes = document.querySelectorAll(".product-card.sorvetes");
    sorvetes.forEach((card) => {
        const sabor = card.querySelector("h3").textContent.toLowerCase();
        const iconScoop = card.querySelector(".scoop-part");
        if (iconScoop && coresSabor[sabor]) {
            iconScoop.style.color = coresSabor[sabor];
        }
    });

    const picoles = document.querySelectorAll(
        '.product-card.picole-leite, .product-card.picole-fruta'
    );
    picoles.forEach((card) => {
        const sabor = card.querySelector("h3").textContent.toLowerCase().replace(' suíço', 'suico');
        const icon = card.querySelector(".custom-popsicle-icon path");
        if (icon && coresSabor[sabor]) {
            icon.style.fill = coresSabor[sabor];
        }
    });

    const ituzinhos = document.querySelectorAll(".product-card.ituzinho");
    ituzinhos.forEach((card) => {
        const sabor = card.querySelector("h3").textContent.toLowerCase();
        const icon = card.querySelector(".ituzinho-icon path");
        if (icon && coresSabor[sabor]) {
            icon.style.fill = coresSabor[sabor];
        }
    });

    const skimos = document.querySelectorAll(".product-card.skimo");
    skimos.forEach((card) => {
        const sabor = card.querySelector("h3").textContent.toLowerCase();
        const icon = card.querySelector(".skimo-icon .inner-ice-cream");
        if (icon && coresSabor[sabor]) {
            icon.style.fill = coresSabor[sabor];
        }
    });
});
