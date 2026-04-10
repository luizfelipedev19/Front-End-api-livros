document.addEventListener('DOMContentLoaded', () => {
    const elementos = {
        totalLivros: document.getElementById('totalLivros'),
        totalLendo: document.getElementById('totalLendo'),
        totalLidos: document.getElementById('totalLidos'),
        listaLivros: document.getElementById('listaLivros'),
        buscarLivro: document.getElementById('buscarLivro'),
        filtroAutor: document.getElementById('filtroAutor'),
        filtroAno: document.getElementById('filtroAno'),
        filtroStatus: document.getElementById('filtroStatus')
    };

    let livrosOriginais = [];
    let livrosFiltrados = [];

    iniciar();

    async function iniciar() {
        configurarEventos();
        await carregarLivros();
    }

    function configurarEventos() {
        if (elementos.buscarLivro) {
            elementos.buscarLivro.addEventListener('input', aplicarFiltros);
        }

        if (elementos.filtroAutor) {
            elementos.filtroAutor.addEventListener('change', aplicarFiltros);
        }

        if (elementos.filtroAno) {
            elementos.filtroAno.addEventListener('change', aplicarFiltros);
        }

        if (elementos.filtroStatus) {
            elementos.filtroStatus.addEventListener('change', aplicarFiltros);
        }
    }

    async function carregarLivros() {
        try {
            const response = await fetch('/Front-Biblioteca/home/livros', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            let data = {};

            try {
                data = await response.json();
            } catch (error) {
                throw new Error('Resposta inválida do servidor.');
            }

            if (!response.ok || !data.success) {
                mostrarPopup(data.mensagem || 'Erro ao carregar livros.', 'erro');
                return;
            }

            const detail = data.detail || {};
            livrosOriginais = Array.isArray(detail.livros) ? detail.livros : [];
            livrosFiltrados = [...livrosOriginais];

            preencherFiltros(livrosOriginais);
            atualizarResumo(livrosOriginais);
            renderizarLivros(livrosFiltrados);

        } catch (error) {
            console.error('Erro ao carregar livros:', error);
            mostrarPopup('Erro ao carregar livros. Tente novamente.', 'erro');
        }
    }

    function preencherFiltros(livros) {
        preencherFiltroAutores(livros);
        preencherFiltroAnos(livros);
    }

    function preencherFiltroAutores(livros) {
        if (!elementos.filtroAutor) return;

        const autorSelecionado = elementos.filtroAutor.value || '';
        const autores = [...new Set(
            livros
                .map(livro => (livro.autor || '').trim())
                .filter(Boolean)
        )].sort((a, b) => a.localeCompare(b, 'pt-BR'));

        elementos.filtroAutor.innerHTML = '<option value="">Todos os autores</option>';

        autores.forEach(autor => {
            const option = document.createElement('option');
            option.value = autor;
            option.textContent = autor;
            elementos.filtroAutor.appendChild(option);
        });

        elementos.filtroAutor.value = autores.includes(autorSelecionado) ? autorSelecionado : '';
    }

    function preencherFiltroAnos(livros) {
        if (!elementos.filtroAno) return;

        const anoSelecionado = elementos.filtroAno.value || '';
        const anos = [...new Set(
            livros
                .map(livro => String(livro.ano || '').trim())
                .filter(Boolean)
        )].sort((a, b) => Number(b) - Number(a));

        elementos.filtroAno.innerHTML = '<option value="">Todos os anos</option>';

        anos.forEach(ano => {
            const option = document.createElement('option');
            option.value = ano;
            option.textContent = ano;
            elementos.filtroAno.appendChild(option);
        });

        elementos.filtroAno.value = anos.includes(anoSelecionado) ? anoSelecionado : '';
    }

    function aplicarFiltros() {
        const termoBusca = (elementos.buscarLivro?.value || '').trim().toLowerCase();
        const autorSelecionado = elementos.filtroAutor?.value || '';
        const anoSelecionado = elementos.filtroAno?.value || '';
        const statusSelecionado = elementos.filtroStatus?.value || '';

        livrosFiltrados = livrosOriginais.filter(livro => {
            const titulo = (livro.titulo || '').toLowerCase();
            const autor = (livro.autor || '').toLowerCase();
            const genero = (livro.genero || '').toLowerCase();
            const anotacoes = (livro.anotacoes || '').toLowerCase();
            const ano = String(livro.ano || '');
            const status = livro.status || '';

            const correspondeBusca =
                !termoBusca ||
                titulo.includes(termoBusca) ||
                autor.includes(termoBusca) ||
                genero.includes(termoBusca) ||
                anotacoes.includes(termoBusca);

            const correspondeAutor = !autorSelecionado || (livro.autor || '') === autorSelecionado;
            const correspondeAno = !anoSelecionado || ano === anoSelecionado;
            const correspondeStatus = !statusSelecionado || status === statusSelecionado;

            return correspondeBusca && correspondeAutor && correspondeAno && correspondeStatus;
        });

        atualizarResumo(livrosFiltrados);
        renderizarLivros(livrosFiltrados);
    }

    function atualizarResumo(livros) {
        const total = livros.length;
        const lendo = livros.filter(livro => livro.status === 'lendo').length;
        const lidos = livros.filter(livro => livro.status === 'lido').length;

        if (elementos.totalLivros) {
            elementos.totalLivros.textContent = total;
        }

        if (elementos.totalLendo) {
            elementos.totalLendo.textContent = lendo;
        }

        if (elementos.totalLidos) {
            elementos.totalLidos.textContent = lidos;
        }
    }

    function renderizarLivros(livros) {
        if (!elementos.listaLivros) return;

        if (!livros.length) {
            elementos.listaLivros.innerHTML = `
                <p class="sem-livros">
                    Nenhum livro encontrado com os filtros informados.
                </p>
            `;
            return;
        }

        elementos.listaLivros.innerHTML = livros.map(criarCardLivro).join('');
    }

    function criarCardLivro(livro) {
        const id = escapeHtml(String(livro.id_livro || ''));
        const titulo = escapeHtml(livro.titulo || '');
        const autor = escapeHtml(livro.autor || '');
        const ano = escapeHtml(String(livro.ano || ''));
        const genero = escapeHtml(livro.genero || '');
        const status = escapeHtml(livro.status || '');
        const avaliacao = Number(livro.avaliacao || 0);
        const anotacoes = escapeHtml(livro.anotacoes || '');

        const statusTexto = (livro.status || '') === 'quero_ler'
            ? 'Quero ler'
            : capitalizarPrimeiraLetra(livro.status || '');

        const estrelas = avaliacao > 0
            ? `
                <span class="livro-avaliacao">
                    ${'★'.repeat(avaliacao)}${'☆'.repeat(5 - avaliacao)}
                </span>
            `
            : '';

        const generoHtml = genero
            ? `<span class="livro-genero">${genero}</span>`
            : '';

        return `
            <article class="livro-card"
                data-id="${id}"
                data-titulo="${titulo}"
                data-autor="${autor}"
                data-ano="${ano}"
                data-genero="${genero}"
                data-status="${status}"
                data-avaliacao="${escapeHtml(String(livro.avaliacao || ''))}"
                data-anotacoes="${anotacoes}"
            >
                <div class="livro-card-header">
                    <span class="livro-status ${status}">
                        ${escapeHtml(statusTexto)}
                    </span>
                    ${estrelas}
                </div>

                <h3>${titulo}</h3>
                <p>${autor}</p>
                <span>${ano}</span>
                ${generoHtml}
            </article>
        `;
    }

    function capitalizarPrimeiraLetra(valor) {
        if (!valor) return '';
        return valor.charAt(0).toUpperCase() + valor.slice(1);
    }

    function escapeHtml(texto) {
        return String(texto)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
});




//Controler só vai enviar os fetchs para o controllador php (app)