
const logo = document.querySelector(".logo");
const menu = document.querySelector(".menu");
const menuOverlay = document.getElementById("menuOverlay");

if (logo && menu) {
    logo.addEventListener("click", () => {
        menu.classList.toggle("aberto");
        if (menuOverlay) menuOverlay.classList.toggle("aberto");
    });

    if(menuOverlay) {
        menuOverlay.addEventListener("click", () => {
            menu.classList.remove("aberto");
            menuOverlay.classList.remove("aberto");
        });
    }
}
function abrirModalGenerico(overlay) {
    overlay.classList.remove("hidden");
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            overlay.classList.add("visivel");
        });
    });
}

function fecharModalGenerico(overlay, callback) {
    overlay.classList.remove("visivel");
    setTimeout(() => {
        overlay.classList.add("hidden");
        if (callback) callback();
    }, 250);
}


// Estrelas
const estrelas = document.querySelectorAll(".estrela");
const inputAvaliacao = document.getElementById("avaliacao");

estrelas.forEach((estrela) => {
    estrela.addEventListener("click", () => {
        const valor = parseInt(estrela.dataset.valor);
        inputAvaliacao.value = valor;

        estrelas.forEach((e) => {
            e.classList.toggle("ativa", parseInt(e.dataset.valor) <= valor);
        });
    });

    estrela.addEventListener("mouseover", () => {
        const valor = parseInt(estrela.dataset.valor);
        estrelas.forEach((e) => {
            e.classList.toggle("ativa", parseInt(e.dataset.valor) <= valor);
        });
    });

    estrela.addEventListener("mouseout", () => {
        const valorAtual = parseInt(inputAvaliacao.value) || 0;
        estrelas.forEach((e) => {
            e.classList.toggle("ativa", parseInt(e.dataset.valor) <= valorAtual);
        });
    });
});

function mostrarPopup(mensagem, tipo = "success") {
    const toast = document.getElementById("toast");

    if (!toast) {
        console.error("Elemento #toast não encontrado");
        return;
    }

    toast.textContent = mensagem;
    toast.className = `toast ${tipo}`;
    toast.classList.remove("hidden");

    setTimeout(() => {
        toast.classList.add("hidden");
    }, 3000);
}