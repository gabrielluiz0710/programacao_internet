document.addEventListener("DOMContentLoaded", () => {
    window.scrollTo(0, 0);

    const tabButtons = document.querySelectorAll(".tab-button");
    const productCards = document.querySelectorAll(".product-card");

    tabButtons.forEach((button) => {
        button.addEventListener("click", () => {
            tabButtons.forEach((btn) => btn.classList.remove("active"));
            button.classList.add("active");

            const category = button.getAttribute("data-category");

            productCards.forEach((card) => {
                card.style.transition = "none";
                card.style.display = "none";

                if (card.classList.contains(category)) {
                    setTimeout(() => {
                        card.style.display = "block";
                    }, 10);
                }
            });
        });
    });

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                }
            });
        },
        {
            threshold: 0.1,
        }
    );

    productCards.forEach((card) => {
        observer.observe(card);
    });

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
