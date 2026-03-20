const abrirModalLivro = document.getElementById("abrirModalLivro");
const fecharModalLivro = document.getElementById("fecharModalLivro");
const cancelarModalLivro = document.getElementById("cancelarModalLivro");
const modalLivro = document.getElementById("modalLivro");
const formLivro = document.getElementById("formLivro");

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