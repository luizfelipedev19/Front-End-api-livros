<?php
/*
|--------------------------------------------------------------------------
| FLASH MESSAGE
|--------------------------------------------------------------------------
| Recupera mensagens temporárias da sessão, normalmente usadas para
| exibir feedback ao usuário depois de alguma ação:
| - livro salvo
| - erro ao editar
| - sucesso ao deletar
*/
require_once __DIR__ . '/../Utils/flash.php';
$flash = getFlash();

/*
|--------------------------------------------------------------------------
| DADOS DO USUÁRIO LOGADO
|--------------------------------------------------------------------------
| busca os dados básicos da sessão para personalizar a home.
| Caso o usuário não tenha foto cadastrada, é usado um avatar padrão.
*/
$nomeUsuario = $_SESSION['nome'] ?? 'Usuário';
$fotoPerfil  = $_SESSION['foto_perfil'] ?? null;
$srcFoto     = $fotoPerfil
    ? htmlspecialchars($fotoPerfil, ENT_QUOTES, 'UTF-8')
    : '/Front-Biblioteca/public/img/avatar.png';

/*
|--------------------------------------------------------------------------
| RESUMO DA BIBLIOTECA DO USUÁRIO
|--------------------------------------------------------------------------
| Esses valores abastecem os cards do topo da home:
| - total de livros
| - quantidade em leitura
| - quantidade já lida
|
| O controller envia $livros e $total, e aqui a view apenas organiza
| esses dados para exibição.
*/
$totalLivros = $total ?? 0;
$totalLendo  = count(array_filter($livros ?? [], fn($l) => ($l['status'] ?? '') === 'lendo'));
$totalLidos  = count(array_filter($livros ?? [], fn($l) => ($l['status'] ?? '') === 'lido'));
?>

<head>
    <link rel="stylesheet" href="/Front-Biblioteca/public/css/home.css">
</head>

<!--
|--------------------------------------------------------------------------
| TOAST / POPUP GLOBAL
|--------------------------------------------------------------------------
| Elemento usado para exibir mensagens rápidas na tela via JS.
| Exemplo:
| - "Livro salvo com sucesso"
| - "Erro ao deletar livro"
-->
<div id="toast" class="toast hidden"></div>

<div class="dashboard">

    <!--
    |--------------------------------------------------------------------------
    | SIDEBAR / MENU LATERAL
    |--------------------------------------------------------------------------
    | Estrutura principal do menu lateral da aplicação.
    | O overlay geralmente é usado no menu responsivo para fechar ao clicar fora.
    -->
    <aside class="sidebar">
        <div class="logo">BM</div>
        <div id="menuOverlay" class="menu-overlay"></div>

        <nav class="menu">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Meus livros</a></li>
                <li><a href="#">Favoritos</a></li>
                <li><a href="#">Citações</a></li>
                <li><a href="/Front-Biblioteca/perfil">Perfil</a></li>
            </ul>
        </nav>
    </aside>

    <main class="content">

        <!--
        |--------------------------------------------------------------------------
        | TOPO DA HOME
        |--------------------------------------------------------------------------
        | Exibe o nome da aplicação, o nome do usuário logado,
        | a foto de perfil e o link de logout.
        -->
        <header class="topbar">
            <h1>BookManager</h1>

            <div class="user-area">
                <span><?= htmlspecialchars($nomeUsuario, ENT_QUOTES, 'UTF-8') ?></span>

                <img
                    src="<?= $srcFoto ?>"
                    alt="Foto do usuário"
                    id="fotoPerfil"
                    title="Clique para alterar a sua foto"
                >

                <a href="/Front-Biblioteca/logout">Sair</a>
            </div>
        </header>

        <!--
        |--------------------------------------------------------------------------
        | CARDS DE RESUMO
        |--------------------------------------------------------------------------
        | Mostram uma visão rápida da biblioteca do usuário.
        | Os IDs são úteis para atualização dinâmica via JavaScript.
        -->
        <section class="cards-resumo">
            <div class="card">
                <p>Total de livros</p>
                <h2 id="totalLivros"><?= $totalLivros ?></h2>
            </div>

            <div class="card">
                <p>Lendo</p>
                <h2 id="totalLendo"><?= $totalLendo ?></h2>
            </div>

            <div class="card">
                <p>Lidos</p>
                <h2 id="totalLidos"><?= $totalLidos ?></h2>
            </div>
        </section>

        <!--
        |--------------------------------------------------------------------------
        | AÇÕES E FILTROS DA HOME
        |--------------------------------------------------------------------------
        | Aqui ficam:
        | - botão de cadastrar novo livro
        | - busca por texto
        | - filtros por autor, ano e status
        |
        | Esses filtros podem ser preenchidos e manipulados pelo JS.
        -->
        <section class="acoes-filtros">
            <button type="button" class="btn-cadastrar" id="abrirModalLivro">
                + Cadastrar novo livro
            </button>

            <div class="busca-filtros">
                <input type="text" id="buscarLivro" placeholder="Buscar livro...">

                <div class="filtros">
                    <select id="filtroAutor">
                        <option value="">Todos os autores</option>
                    </select>

                    <select id="filtroAno">
                        <option value="">Todos os anos</option>
                    </select>

                    <select id="filtroStatus">
                        <option value="">Todos os status</option>
                        <option value="quero_ler">Quero ler</option>
                        <option value="lendo">Lendo</option>
                        <option value="lido">Lido</option>
                    </select>
                </div>
            </div>
        </section>

        <!--
        |--------------------------------------------------------------------------
        | LISTA DE LIVROS
        |--------------------------------------------------------------------------
        | Exibe os livros do usuário na home.
        |
        | Se não houver livros, mostramos uma mensagem orientando o usuário
        | a começar o cadastro.
        |
        | Cada card recebe vários atributos data-* para facilitar a integração
        | com o JavaScript. Esses dados são usados para:
        | - abrir o modal de detalhes
        | - editar livro
        | - preencher formulário automaticamente
        -->
        <section class="lista-livros" id="listaLivros">
            <?php if (empty($livros)): ?>
                <p class="sem-livros">
                    Nenhum livro cadastrado ainda. Clique em "+ Cadastrar novo livro" para começar!
                </p>
            <?php else: ?>
                <?php foreach ($livros as $livro): ?>
                    <article class="livro-card"
                        data-id="<?= htmlspecialchars((string)($livro['id_livro'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                        data-titulo="<?= htmlspecialchars($livro['titulo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        data-autor="<?= htmlspecialchars($livro['autor'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        data-ano="<?= htmlspecialchars((string)($livro['ano'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                        data-genero="<?= htmlspecialchars($livro['genero'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        data-status="<?= htmlspecialchars($livro['status'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        data-avaliacao="<?= htmlspecialchars((string)($livro['avaliacao'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                        data-anotacoes="<?= htmlspecialchars($livro['anotacoes'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    >
                        <div class="livro-card-header">
                            <span class="livro-status <?= htmlspecialchars($livro['status'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <?= ($livro['status'] ?? '') === 'quero_ler'
                                    ? 'Quero ler'
                                    : ucfirst($livro['status'] ?? '') ?>
                            </span>

                            <?php if (!empty($livro['avaliacao'])): ?>
                                <span class="livro-avaliacao">
                                    <?= str_repeat('★', (int)$livro['avaliacao']) ?>
                                    <?= str_repeat('☆', 5 - (int)$livro['avaliacao']) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <h3><?= htmlspecialchars($livro['titulo'] ?? '', ENT_QUOTES, 'UTF-8') ?></h3>
                        <p><?= htmlspecialchars($livro['autor'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                        <span><?= htmlspecialchars((string)($livro['ano'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>

                        <?php if (!empty($livro['genero'])): ?>
                            <span class="livro-genero">
                                <?= htmlspecialchars($livro['genero'], ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

    </main>
</div>

<!--
|--------------------------------------------------------------------------
| MODAL DE CADASTRO / EDIÇÃO DE LIVRO
|--------------------------------------------------------------------------
| Esse modal é usado tanto para cadastrar um novo livro quanto
| para editar um já existente.
|
| O JS decide o modo do formulário:
| - cadastro -> limpa o formulário
| - edição -> preenche os dados do livro selecionado
-->
<div class="modal-overlay hidden" id="modalLivro">
    <div class="modal">
        <div class="modal-header">
            <h2>Cadastrar livro</h2>
            <button type="button" class="fechar-modal" id="fecharModalLivro">&times;</button>
        </div>

        <form id="formLivro" action="/Front-Biblioteca/livros/salvar" method="POST">
            <div class="form-group">
                <input type="hidden" id="id_livro" name="id_livro">

                <label for="titulo">Título *</label>
                <input type="text" id="titulo" name="titulo" placeholder="Digite o título do livro" required>
            </div>

            <div class="form-group">
                <label for="autor">Autor *</label>
                <input type="text" id="autor" name="autor" placeholder="Digite o autor" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ano">Ano *</label>
                    <input type="number" id="ano" name="ano" placeholder="Ex: 2023" required>
                </div>

                <div class="form-group">
                    <label for="genero">Gênero</label>
                    <select name="genero" id="genero">
                        <option value="">Selecione</option>
                        <option value="Tecnologia">Tecnologia</option>
                        <option value="Romance">Romance</option>
                        <option value="Ficção Científica">Ficção Científica</option>
                        <option value="Fantasia">Fantasia</option>
                        <option value="Biografia">Biografia</option>
                        <option value="História">História</option>
                        <option value="Autoajuda">Autoajuda</option>
                        <option value="Negócios">Negócios</option>
                        <option value="Outros">Outros</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="status">Status *</label>
                    <select name="status" id="status">
                        <option value="quero_ler">Quero ler</option>
                        <option value="lendo">Lendo</option>
                        <option value="lido">Lido</option>
                    </select>
                </div>

                <!--
                | Avaliação em estrelas.
                | O valor real fica salvo no input hidden #avaliacao.
                -->
                <div class="form-group">
                    <label>Avaliação</label>
                    <div class="estrelas" id="estrelasContainer">
                        <span class="estrela" data-valor="1">&#9733;</span>
                        <span class="estrela" data-valor="2">&#9733;</span>
                        <span class="estrela" data-valor="3">&#9733;</span>
                        <span class="estrela" data-valor="4">&#9733;</span>
                        <span class="estrela" data-valor="5">&#9733;</span>
                    </div>
                    <input type="hidden" id="avaliacao" name="avaliacao" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="anotacoes">Anotações</label>
                <textarea
                    id="anotacoes"
                    name="anotacoes"
                    placeholder="Escreva suas anotações, frases marcantes ou resumo..."
                    rows="4"
                ></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancelar" id="cancelarModalLivro">Cancelar</button>
                <button type="submit" class="btn-salvar">Salvar livro</button>
            </div>
        </form>
    </div>
</div>

<!--
|--------------------------------------------------------------------------
| MODAL DE FOTO DE PERFIL
|--------------------------------------------------------------------------
| Permite selecionar e enviar uma nova imagem de perfil.
| O envio é feito para o endpoint /upload-foto.
-->
<div class="modal-overlay hidden" id="modalFoto">
    <div class="modal">
        <div class="modal-header">
            <h2>Alterar Foto de Perfil</h2>
            <button class="fechar-modal" id="fecharModalFoto">&times;</button>
        </div>

        <form action="/Front-Biblioteca/upload-foto" method="POST" enctype="multipart/form-data" id="formFoto">
            <div class="upload-area" id="uploadArea">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>

                <span class="upload-titulo">Arraste sua foto aqui</span>
                <span class="upload-sub">Ou clique para selecionar</span>
                <span class="upload-info">JPG, JPEG ou PNG - máx. 5MB</span>

                <input
                    type="file"
                    name="foto"
                    id="inputFoto"
                    accept="image/jpg, image/jpeg, image/png"
                    style="display: none"
                >
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancelar" id="cancelarModalFoto">Cancelar</button>
                <button type="submit" class="btn-salvar">Salvar</button>
            </div>
        </form>
    </div>
</div>

<!--
|--------------------------------------------------------------------------
| MODAL DE DETALHES DO LIVRO
|--------------------------------------------------------------------------
| Mostra as informações completas do livro clicado.
| A partir daqui o usuário pode:
| - deletar
| - editar
-->
<div class="modal-overlay hidden" id="modalDetalhes">
    <div class="modal modal-detalhes">
        <div class="modal-header">
            <h2 id="detalhesTitulo"></h2>
            <button class="fechar-modal" id="fecharModalDetalhes">&times;</button>
        </div>

        <div class="detalhes-body">
            <div class="detalhes-info">
                <div class="detalhe-item">
                    <span class="detalhe-label">Autor</span>
                    <span id="detalhesAutor"></span>
                </div>

                <div class="detalhe-item">
                    <span class="detalhe-label">Ano</span>
                    <span id="detalhesAno"></span>
                </div>

                <div class="detalhe-item">
                    <span class="detalhe-label">Gênero</span>
                    <span id="detalhesGenero"></span>
                </div>

                <div class="detalhe-item">
                    <span class="detalhe-label">Status</span>
                    <span id="detalhesStatus"></span>
                </div>

                <div class="detalhe-item">
                    <span class="detalhe-label">Avaliação</span>
                    <span id="detalhesAvaliacao"></span>
                </div>
            </div>

            <div class="detalhe-anotacoes" id="detalhesAnotacoesContainer">
                <span class="detalhe-label">Anotações</span>
                <p id="detalhesAnotacoes"></p>
            </div>
        </div>

        <div class="modal-actions">
            <button type="button" class="btn-deletar" id="btnDeletarLivro">Deletar</button>
            <button type="button" class="btn-editar" id="btnEditarLivro">Editar</button>
        </div>
    </div>
</div>

<!--
|--------------------------------------------------------------------------
| SCRIPTS DA PÁGINA
|--------------------------------------------------------------------------
| Ordem importante:
| 1. popup.js -> funções genéricas de modal, toast e estrelas
| 2. inatividade.js -> controle de sessão/tempo
| 3. livroModal.js -> comportamento do modal de livro
| 4. home.js -> lógica específica da home (filtros, fetch, atualização)
| 5. modalFoto.js -> comportamento do modal de foto
|
| Observação:
| Atualmente há dois arquivos de home carregados:
| - /public/Models/home.js
| - /public/Controller/home.js
|
| Isso pode gerar conflito se ambos tentarem controlar a mesma tela.
| Vale revisar se realmente é necessário manter os dois.
-->
<script src="/Front-Biblioteca/public/js/popup.js"></script>
<script src="/Front-Biblioteca/public/js/inatividade.js"></script>
<script src="/Front-Biblioteca/public/js/livroModal.js"></script>
<script src="/Front-Biblioteca/public/Models/home.js"></script>
<script src="/Front-Biblioteca/public/js/modalFoto.js"></script>
<script src="/Front-Biblioteca/public/Controller/home.js"></script>

<!--
|--------------------------------------------------------------------------
| EXIBIÇÃO DE FLASH MESSAGE
|--------------------------------------------------------------------------
| Se existir uma mensagem de flash, ela é exibida automaticamente
| ao carregar a página através do popup/toast.
-->
<?php if ($flash): ?>
    <script>
        mostrarPopup(
            "<?= htmlspecialchars($flash['mensagem'], ENT_QUOTES, 'UTF-8') ?>",
            "<?= htmlspecialchars($flash['tipo'], ENT_QUOTES, 'UTF-8') ?>"
        );
    </script>
<?php endif; ?>