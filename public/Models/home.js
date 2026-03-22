
function abrirModal() {
    modalLivro.classList.remove("hidden");
}

function fecharModal() {
    modalLivro.classList.add("hidden");
}

if (abrirModalLivro) {
    abrirModalLivro.addEventListener("click", abrirModal);
}

if (fecharModalLivro) {
    fecharModalLivro.addEventListener("click", fecharModal);
}

if (cancelarModalLivro) {
    cancelarModalLivro.addEventListener("click", fecharModal);
}

if (modalLivro) {
    modalLivro.addEventListener("click", function (event) {
        if (event.target === modalLivro) {
            fecharModal();
        }
    });
}