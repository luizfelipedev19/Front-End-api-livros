// ==========================================================
// CONTROLE DO MENU LATERAL E DOS MODAIS GENÉRICOS DA HOME
// ==========================================================
//
// Esse arquivo concentra algumas funções reutilizáveis da tela:
// - abrir/fechar o menu lateral
// - abrir/fechar modais com animação
// - controlar a avaliação por estrelas
// - exibir mensagens rápidas na tela (toast / popup)
//
// A ideia aqui é centralizar comportamentos genéricos que podem
// ser usados por outros scripts da página, como livroModal.js.
//

// Elementos principais do menu lateral
const logo = document.querySelector(".logo");
const menu = document.querySelector(".menu");
const menuOverlay = document.getElementById("menuOverlay");

// ==========================================================
// MENU LATERAL
// ==========================================================
// Ao clicar na logo, o menu lateral abre ou fecha.
// O overlay escurece o fundo e também pode ser clicado
// para fechar o menu.
if (logo && menu) {
    logo.addEventListener("click", () => {
        menu.classList.toggle("aberto");

        if (menuOverlay) {
            menuOverlay.classList.toggle("aberto");
        }
    });

    if (menuOverlay) {
        menuOverlay.addEventListener("click", () => {
            menu.classList.remove("aberto");
            menuOverlay.classList.remove("aberto");
        });
    }
}

// ==========================================================
// MODAL GENÉRICO - ABRIR
// ==========================================================
// Essa função abre qualquer modal/overlay que siga o padrão
// de classes "hidden" e "visivel".
//
// O requestAnimationFrame duplo ajuda a garantir que a transição
// CSS seja aplicada corretamente, dando tempo do navegador
// perceber a mudança entre os estados.
function abrirModalGenerico(overlay) {
    overlay.classList.remove("hidden");

    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            overlay.classList.add("visivel");
        });
    });
}

// ==========================================================
// MODAL GENÉRICO - FECHAR
// ==========================================================
// Remove a classe de visibilidade e, após o tempo da animação,
// oculta completamente o modal.
//
// O callback é opcional e serve para executar alguma ação
// depois que o modal terminar de fechar, por exemplo:
// abrir outro modal em seguida.
function fecharModalGenerico(overlay, callback) {
    overlay.classList.remove("visivel");

    setTimeout(() => {
        overlay.classList.add("hidden");

        if (callback) {
            callback();
        }
    }, 250);
}

// ==========================================================
// AVALIAÇÃO POR ESTRELAS
// ==========================================================
//
// Aqui controlamos a experiência visual da nota do livro.
// O usuário pode:
// - clicar para definir a nota
// - passar o mouse para pré-visualizar
// - tirar o mouse e voltar ao valor salvo
//
// O valor escolhido é armazenado no input hidden #avaliacao.
const estrelas = document.querySelectorAll(".estrela");
const inputAvaliacao = document.getElementById("avaliacao");

estrelas.forEach((estrela) => {
    // Ao clicar em uma estrela, definimos a nota final
    estrela.addEventListener("click", () => {
        const valor = parseInt(estrela.dataset.valor);
        inputAvaliacao.value = valor;

        estrelas.forEach((e) => {
            e.classList.toggle("ativa", parseInt(e.dataset.valor) <= valor);
        });
    });

    // Ao passar o mouse, fazemos uma prévia visual da nota
    estrela.addEventListener("mouseover", () => {
        const valor = parseInt(estrela.dataset.valor);

        estrelas.forEach((e) => {
            e.classList.toggle("ativa", parseInt(e.dataset.valor) <= valor);
        });
    });

    // Ao tirar o mouse, restauramos a nota realmente salva
    estrela.addEventListener("mouseout", () => {
        const valorAtual = parseInt(inputAvaliacao.value) || 0;

        estrelas.forEach((e) => {
            e.classList.toggle("ativa", parseInt(e.dataset.valor) <= valorAtual);
        });
    });
});

// ==========================================================
// POPUP / TOAST DE MENSAGEM
// ==========================================================
//
// Exibe uma mensagem rápida para o usuário.
// Pode ser usada para feedback de sucesso, erro, aviso etc.
//
// Exemplo:
// mostrarPopup("Livro salvo com sucesso", "success");
// mostrarPopup("Erro ao deletar livro", "erro");
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