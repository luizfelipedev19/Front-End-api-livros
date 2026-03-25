const fotoPerfil = document.getElementById("fotoPerfil");
const modalFoto = document.getElementById("modalFoto");
const fecharModalFoto = document.getElementById("fecharModalFoto");
const cancelarModalFoto = document.getElementById("cancelarModalFoto");
const uploadArea = document.getElementById("uploadArea");
const inputFoto = document.getElementById("inputFoto");


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