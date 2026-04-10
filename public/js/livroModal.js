document.addEventListener("DOMContentLoaded", () => {
    /*
     * =========================================================
     * ELEMENTOS PRINCIPAIS DA TELA
     * =========================================================
     * Aqui pegamos os elementos do DOM que vamos usar ao longo
     * do arquivo para abrir/fechar modais, editar e deletar livros.
     */
    const abrirModalLivroBtn = document.getElementById("abrirModalLivro");
    const fecharModalLivroBtn = document.getElementById("fecharModalLivro");
    const cancelarModalLivroBtn = document.getElementById("cancelarModalLivro");
    const modalLivro = document.getElementById("modalLivro");
    const formLivro = document.getElementById("formLivro");

    const modalDetalhes = document.getElementById("modalDetalhes");
    const fecharModalDetalhesBtn = document.getElementById("fecharModalDetalhes");

    const btnDeletarLivro = document.getElementById("btnDeletarLivro");
    const btnEditarLivro = document.getElementById("btnEditarLivro");

    /*
     * Guarda o ID do livro atualmente selecionado.
     * Esse valor é usado principalmente nas ações de editar e deletar.
     */
    let livroAtualId = null;

    /*
     * =========================================================
     * FUNÇÕES DE ABERTURA E FECHAMENTO DO MODAL DE LIVRO
     * =========================================================
     * Essas funções dependem das funções genéricas
     * abrirModalGenerico() e fecharModalGenerico().
     * Caso elas não existam, mostramos um erro no console.
     */
    function abrirModalLivro() {
        if (typeof abrirModalGenerico !== "function") {
            console.error("Função abrirModalGenerico não encontrada.");
            return;
        }

        abrirModalGenerico(modalLivro);
    }

    function fecharModalLivro() {
        if (typeof fecharModalGenerico !== "function") {
            console.error("Função fecharModalGenerico não encontrada.");
            return;
        }

        fecharModalGenerico(modalLivro);
    }

    /*
     * =========================================================
     * ABRIR MODAL DE DETALHES DO LIVRO
     * =========================================================
     * Recebe o card clicado, lê os dados armazenados nos data-*,
     * preenche o modal de detalhes e abre esse modal.
     */
    function abrirDetalhesLivro(card) {
        if (!card || !modalDetalhes) return;

        const id = card.dataset.id || "";
        const titulo = card.dataset.titulo || "";
        const autor = card.dataset.autor || "";
        const ano = card.dataset.ano || "";
        const genero = card.dataset.genero || "Não informado";
        const status = card.dataset.status || "";
        const avaliacao = card.dataset.avaliacao || "";
        const anotacoes = card.dataset.anotacoes || "Nenhuma anotação";

        // Salva o ID do livro selecionado para uso posterior
        livroAtualId = id;

        // Mapeia os status internos para um texto mais amigável na tela
        const statusMap = {
            quero_ler: "Quero ler",
            lendo: "Lendo",
            lido: "Lido"
        };

        // Preenche os campos do modal de detalhes
        document.getElementById("detalhesTitulo").textContent = titulo;
        document.getElementById("detalhesAutor").textContent = autor;
        document.getElementById("detalhesAno").textContent = ano;
        document.getElementById("detalhesGenero").textContent = genero;
        document.getElementById("detalhesAnotacoes").textContent = anotacoes;
        document.getElementById("detalhesStatus").textContent = statusMap[status] || status;

        // Monta a exibição das estrelas de avaliação
        document.getElementById("detalhesAvaliacao").textContent = avaliacao
            ? "★".repeat(parseInt(avaliacao, 10)) + "☆".repeat(5 - parseInt(avaliacao, 10))
            : "Sem avaliação";

        if (typeof abrirModalGenerico !== "function") {
            console.error("Função abrirModalGenerico não encontrada.");
            return;
        }

        abrirModalGenerico(modalDetalhes);
    }

    /*
     * =========================================================
     * PREPARAR FORMULÁRIO PARA NOVO CADASTRO
     * =========================================================
     * Limpa todos os campos do formulário e ajusta o modal
     * para o modo "Cadastrar livro".
     */
    function prepararFormularioNovoLivro() {
        if (!formLivro) return;

        formLivro.reset();

        const idLivro = document.getElementById("id_livro");
        const avaliacao = document.getElementById("avaliacao");
        const tituloModal = document.querySelector("#modalLivro .modal-header h2");

        if (idLivro) idLivro.value = "";
        if (avaliacao) avaliacao.value = "";
        if (tituloModal) tituloModal.textContent = "Cadastrar livro";

        // Remove a marcação visual das estrelas
        document.querySelectorAll(".estrela").forEach((estrela) => {
            estrela.classList.remove("ativa");
        });
    }

    /*
     * =========================================================
     * PREENCHER FORMULÁRIO PARA EDIÇÃO
     * =========================================================
     * Recebe o card do livro e joga os dados dele no formulário,
     * transformando o modal em modo de edição.
     */
    function preencherFormularioEdicao(card) {
        if (!card) return;

        document.getElementById("id_livro").value = livroAtualId || "";
        document.getElementById("titulo").value = card.dataset.titulo || "";
        document.getElementById("autor").value = card.dataset.autor || "";
        document.getElementById("ano").value = card.dataset.ano || "";
        document.getElementById("genero").value = card.dataset.genero || "";
        document.getElementById("status").value = card.dataset.status || "quero_ler";
        document.getElementById("anotacoes").value = card.dataset.anotacoes || "";

        const avaliacao = card.dataset.avaliacao || "";
        document.getElementById("avaliacao").value = avaliacao;

        // Marca as estrelas de acordo com a avaliação do livro
        document.querySelectorAll(".estrela").forEach((estrela) => {
            const valor = parseInt(estrela.dataset.valor, 10);
            estrela.classList.toggle("ativa", !!avaliacao && valor <= parseInt(avaliacao, 10));
        });

        const tituloModal = document.querySelector("#modalLivro .modal-header h2");
        if (tituloModal) {
            tituloModal.textContent = "Editar livro";
        }
    }

    /*
     * =========================================================
     * EVENTOS DO MODAL DE CADASTRO/EDIÇÃO
     * =========================================================
     */

    // Ao clicar em "Cadastrar novo livro", limpamos o formulário e abrimos o modal
    if (abrirModalLivroBtn) {
        abrirModalLivroBtn.addEventListener("click", () => {
            prepararFormularioNovoLivro();
            abrirModalLivro();
        });
    }

    // Fecha o modal pelo botão X
    if (fecharModalLivroBtn) {
        fecharModalLivroBtn.addEventListener("click", fecharModalLivro);
    }

    // Fecha o modal pelo botão "Cancelar"
    if (cancelarModalLivroBtn) {
        cancelarModalLivroBtn.addEventListener("click", fecharModalLivro);
    }

    // Fecha o modal ao clicar fora da caixa
    if (modalLivro) {
        modalLivro.addEventListener("click", (event) => {
            if (event.target === modalLivro) {
                fecharModalLivro();
            }
        });
    }

    /*
     * =========================================================
     * EVENTOS DO MODAL DE DETALHES
     * =========================================================
     */

    // Fecha o modal de detalhes
    if (fecharModalDetalhesBtn && modalDetalhes) {
        fecharModalDetalhesBtn.addEventListener("click", () => {
            if (typeof fecharModalGenerico !== "function") {
                console.error("Função fecharModalGenerico não encontrada.");
                return;
            }

            fecharModalGenerico(modalDetalhes);
        });
    }

    /*
     * =========================================================
     * CLIQUE NOS CARDS DE LIVRO
     * =========================================================
     * Usamos delegação de eventos para funcionar mesmo quando
     * os cards forem recriados dinamicamente pelo home.js.
     */
    document.addEventListener("click", (event) => {
        const card = event.target.closest(".livro-card");
        if (!card) return;

        abrirDetalhesLivro(card);
    });

    /*
     * =========================================================
     * DELETAR LIVRO
     * =========================================================
     * Envia um POST para o controller PHP do front, que por sua vez
     * deve repassar a ação para a API.
     */
    if (btnDeletarLivro) {
        btnDeletarLivro.addEventListener("click", async () => {
            if (!livroAtualId) {
                mostrarPopup("Livro inválido para exclusão.", "erro");
                return;
            }

            if (!confirm("Tem certeza que deseja deletar este livro?")) return;

            try {
                const resposta = await fetch("/Front-Biblioteca/livro/deletar", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json"
                    },
                    body: `id_livro=${encodeURIComponent(livroAtualId)}`
                });

                if (!resposta.ok) {
                    throw new Error("Erro ao deletar livro.");
                }

                // Fecha o modal de detalhes após exclusão
                if (typeof fecharModalGenerico === "function" && modalDetalhes) {
                    fecharModalGenerico(modalDetalhes);
                }

                // Recarrega a página para atualizar a lista
                window.location.reload();
            } catch (error) {
                console.error(error);
                mostrarPopup("Erro ao deletar livro", "erro");
            }
        });
    }

    /*
     * =========================================================
     * EDITAR LIVRO
     * =========================================================
     * Fecha o modal de detalhes, preenche o formulário com os dados
     * do livro selecionado e abre o modal de cadastro em modo edição.
     */
    if (btnEditarLivro) {
        btnEditarLivro.addEventListener("click", () => {
            const card = document.querySelector(`.livro-card[data-id="${livroAtualId}"]`);
            if (!card) return;

            if (typeof fecharModalGenerico !== "function") {
                console.error("Função fecharModalGenerico não encontrada.");
                return;
            }

            fecharModalGenerico(modalDetalhes, () => {
                preencherFormularioEdicao(card);

                if (typeof abrirModalGenerico !== "function") {
                    console.error("Função abrirModalGenerico não encontrada.");
                    return;
                }

                abrirModalGenerico(modalLivro);
            });
        });
    }
});