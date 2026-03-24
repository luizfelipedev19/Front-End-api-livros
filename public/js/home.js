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

const fotoPerfil = document.getElementById("fotoPerfil");
const modalFoto = document.getElementById("modalFoto");
const fecharModalFoto = document.getElementById("fecharModalFoto");
const cancelarModalFoto = document.getElementById("cancelarModalFoto");
const uploadArea = document.getElementById("uploadArea");
const inputFoto = document.getElementById("inputFoto");

const abrirModalLivro = document.getElementById("abrirModalLivro");
const fecharModalLivro = document.getElementById("fecharModalLivro");
const cancelarModalLivro = document.getElementById("cancelarModalLivro");
const modalLivro = document.getElementById("modalLivro");
const formLivro = document.getElementById("formLivro");

const modalDetalhes = document.getElementById("modalDetalhes");
const fecharModalDetalhes = document.getElementById("fecharModalDetalhes");
let livroAtualId = null;

// Abre o modal ao clicar na foto
if (fotoPerfil) {
    fotoPerfil.addEventListener("click", () => {
        abrirModalGenerico(modalFoto);
    });
}

// Fecha modal foto
function fecharModalFotoFn() {
    fecharModalGenerico(modalFoto, () => {
        inputFoto.value = "";
        uploadArea.classList.remove("tem-arquivo");
        uploadArea.querySelector(".upload-titulo").textContent = "Arraste sua foto aqui";
        uploadArea.querySelector(".upload-sub").textContent = "ou clique para selecionar";
    });
}

if (fecharModalFoto) fecharModalFoto.addEventListener("click", fecharModalFotoFn);
if (cancelarModalFoto) cancelarModalFoto.addEventListener("click", fecharModalFotoFn);

// Clique na área de upload abre o input
if (uploadArea) {
    uploadArea.addEventListener("click", () => inputFoto.click());
}

// Quando seleciona arquivo
if (inputFoto) {
    inputFoto.addEventListener("change", () => {
        if (inputFoto.files.length > 0) {
            const nome = inputFoto.files[0].name;
            uploadArea.classList.add("tem-arquivo");
            uploadArea.querySelector(".upload-titulo").textContent = nome;
            uploadArea.querySelector(".upload-sub").textContent = "Arquivo selecionado";
        }
    });
}

// Drag and drop
if (uploadArea) {
    uploadArea.addEventListener("dragover", (e) => {
        e.preventDefault();
        uploadArea.classList.add("dragover");
    });

    uploadArea.addEventListener("dragleave", () => {
        uploadArea.classList.remove("dragover");
    });

    uploadArea.addEventListener("drop", (e) => {
        e.preventDefault();
        uploadArea.classList.remove("dragover");

        const arquivo = e.dataTransfer.files[0];
        if (arquivo) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(arquivo);
            inputFoto.files = dataTransfer.files;

            uploadArea.classList.add("tem-arquivo");
            uploadArea.querySelector(".upload-titulo").textContent = arquivo.name;
            uploadArea.querySelector(".upload-sub").textContent = "Arquivo selecionado";
        }
    });
}

// Modal livro
function abrirModal() {
    abrirModalGenerico(modalLivro);
}

function fecharModal() {
    fecharModalGenerico(modalLivro);
}

if (abrirModalLivro) {
    abrirModalLivro.addEventListener("click", () => {
        formLivro.reset();
        formLivro.action = `/livros`;
        document.querySelector("#modalLivro .modal-header h2").textContent = "Cadastrar livro";

        document.getElementById("avaliacao").value = "";
        document.querySelectorAll(".estrela").forEach((e) => e.classList.remove("ativa"));

        abrirModal();
    });
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

// Cards — abre modal de detalhes
document.querySelectorAll(".livro-card").forEach((card) => {
    card.addEventListener("click", () => {
        const id        = card.dataset.id;
        const titulo    = card.dataset.titulo;
        const autor     = card.dataset.autor;
        const ano       = card.dataset.ano;
        const genero    = card.dataset.genero || "Não informado";
        const status    = card.dataset.status;
        const avaliacao = card.dataset.avaliacao;
        const anotacoes = card.dataset.anotacoes || "Nenhuma anotação";

        livroAtualId = id;

        document.getElementById("detalhesTitulo").textContent    = titulo;
        document.getElementById("detalhesAutor").textContent     = autor;
        document.getElementById("detalhesAno").textContent       = ano;
        document.getElementById("detalhesGenero").textContent    = genero;
        document.getElementById("detalhesAnotacoes").textContent = anotacoes;

        const statusMap = { quero_ler: "Quero ler", lendo: "Lendo", lido: "Lido" };
        document.getElementById("detalhesStatus").textContent = statusMap[status] || status;

        document.getElementById("detalhesAvaliacao").textContent = avaliacao
            ? "★".repeat(parseInt(avaliacao)) + "☆".repeat(5 - parseInt(avaliacao))
            : "Sem avaliação";

        abrirModalGenerico(modalDetalhes);
    });
});

// Fecha modal detalhes
if (fecharModalDetalhes) {
    fecharModalDetalhes.addEventListener("click", () => {
        fecharModalGenerico(modalDetalhes);
    });
}

// Deletar livro
const btnDeletarLivro = document.getElementById("btnDeletarLivro");
if (btnDeletarLivro) {
    btnDeletarLivro.addEventListener("click", async () => {
        if (!confirm("Tem certeza que deseja deletar este livro?")) return;

        const resposta = await fetch(`/livros/deletar?id=${livroAtualId}`, {
            method: "POST"
        });

        if (resposta.ok) {
            fecharModalGenerico(modalDetalhes);
            window.location.reload();
        } else {
            mostrarPopup("Erro ao deletar livro", "erro");
        }
    });
}

// Editar livro
const btnEditarLivro = document.getElementById("btnEditarLivro");

if (btnEditarLivro) {
    btnEditarLivro.addEventListener("click", () => {
        const card = document.querySelector(`.livro-card[data-id="${livroAtualId}"]`);
        if (!card) return;

        fecharModalGenerico(modalDetalhes, () => {
            document.getElementById("titulo").value = card.dataset.titulo || "";
            document.getElementById("autor").value = card.dataset.autor || "";
            document.getElementById("ano").value = card.dataset.ano || "";
            document.getElementById("genero").value = card.dataset.genero || "";
            document.getElementById("status").value = card.dataset.status || "quero_ler";
            document.getElementById("anotacoes").value = card.dataset.anotacoes || "";

            const avaliacao = card.dataset.avaliacao || "";
            document.getElementById("avaliacao").value = avaliacao;

            const estrelas = document.querySelectorAll(".estrela");
            estrelas.forEach((e) => {
                const valor = parseInt(e.dataset.valor);
                e.classList.toggle("ativa", avaliacao && valor <= parseInt(avaliacao));
            });

            formLivro.action = `/livros/editar?id=${livroAtualId}`;
            document.querySelector("#modalLivro .modal-header h2").textContent = "Editar livro";

            abrirModalGenerico(modalLivro);
        });
    });
}