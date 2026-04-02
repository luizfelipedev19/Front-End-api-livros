const abrirModalLivro = document.getElementById("abrirModalLivro");
const fecharModalLivro = document.getElementById("fecharModalLivro");
const cancelarModalLivro = document.getElementById("cancelarModalLivro");
const modalLivro = document.getElementById("modalLivro");
const formLivro = document.getElementById("formLivro");

const modalDetalhes = document.getElementById("modalDetalhes");
const fecharModalDetalhes = document.getElementById("fecharModalDetalhes");
let livroAtualId = null;





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

        document.getElementById("id_livro").value = "";


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
        const resposta = await fetch(`/Front-Biblioteca/livro/deletar`, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `id_livro=${encodeURIComponent(livroAtualId)}`
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

            document.getElementById("id_livro").value = livroAtualId;
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
            document.querySelector("#modalLivro .modal-header h2").textContent = "Editar livro";

            abrirModalGenerico(modalLivro);
        });
    });
}