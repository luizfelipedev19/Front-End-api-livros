<?php // carregar flash
require_once __DIR__ . '/../Utils/flash.php';
$flash = getFlash();
?>

<?php
$nomeUsuario = $_SESSION['nome'] ?? 'Usuário';
$fotoPerfil  = $_SESSION['foto_perfil'] ?? null;
$srcFoto     = $fotoPerfil ? htmlspecialchars($fotoPerfil) : '/Front-Biblioteca/public/img/avatar.png';
?>

<head>
    <link rel="stylesheet" href="/Front-Biblioteca/public/css/home.css">
</head>

<div id="toast" class="toast hidden"></div>

<div class="dashboard">

    <aside class="sidebar">
        <div class="logo">BM</div>
        <div id="menuOverlay" class="menu-overlay"></div>
        <nav class="menu">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Meus livros</a></li>
                <li><a href="#">Favoritos</a></li>
                <li><a href="#">Adicionar livro</a></li>
                <li><a href="#">Perfil</a></li>
                <li><a href="#">Configurações</a></li>
            </ul>
        </nav>
    </aside>

    <main class="content">
        <header class="topbar">
            <h1>BookManager</h1>
            <div class="user-area">
                <span><?= htmlspecialchars($nomeUsuario) ?></span>
                <img src="<?= $srcFoto ?>" alt="Foto do usuário" id="fotoPerfil" title="Clique para alterar a sua foto">
                <a href="/Front-Biblioteca/logout">Sair</a>
            </div>
        </header>

        <section class="cards-resumo">
            <div class="card">
                <p>Total de livros</p>
                <h2><?= $total ?></h2>
            </div>
            <div class="card">
                <p>Lendo</p>
                <h2><?= count(array_filter($livros, fn($l) => $l['status'] === 'lendo')) ?></h2>
            </div>
            <div class="card">
                <p>Lidos</p>
                <h2><?= count(array_filter($livros, fn($l) => $l['status'] === 'lido')) ?></h2>
            </div>
        </section>

        <section class="acoes-filtros">
            <button type="button" class="btn-cadastrar" id="abrirModalLivro">+ Cadastrar novo livro</button>
            <div class="busca-filtros">
                <input type="text" placeholder="Buscar livro...">
                <div class="filtros">
                    <select><option>Todos os autores</option></select>
                    <select><option>Todos os anos</option></select>
                    <select><option>Todos os status</option></select>
                </div>
            </div>
        </section>

        <section class="lista-livros">
            <?php if (empty($livros)): ?>
                <p class="sem-livros">Nenhum livro cadastrado ainda. Clique em "+ Cadastrar novo livro" para começar!</p>
            <?php else: ?>
                <?php foreach ($livros as $livro): ?>
                    <article class="livro-card"
                        data-id="<?= $livro['id_livro'] ?>"
                        data-titulo="<?= htmlspecialchars($livro['titulo']) ?>"
                        data-autor="<?= htmlspecialchars($livro['autor']) ?>"
                        data-ano="<?= htmlspecialchars($livro['ano']) ?>"
                        data-genero="<?= htmlspecialchars($livro['genero'] ?? '') ?>"
                        data-status="<?= htmlspecialchars($livro['status']) ?>"
                        data-avaliacao="<?= htmlspecialchars($livro['avaliacao'] ?? '') ?>"
                        data-anotacoes="<?= htmlspecialchars($livro['anotacoes'] ?? '') ?>"
                    >
                        <div class="livro-card-header">
                            <span class="livro-status <?= htmlspecialchars($livro['status']) ?>">
                                <?= $livro['status'] === 'quero_ler' ? 'Quero ler' : ucfirst($livro['status']) ?>
                            </span>
                            <?php if ($livro['avaliacao']): ?>
                                <span class="livro-avaliacao">
                                    <?= str_repeat('★', (int)$livro['avaliacao']) ?><?= str_repeat('☆', 5 - (int)$livro['avaliacao']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <h3><?= htmlspecialchars($livro['titulo']) ?></h3>
                        <p><?= htmlspecialchars($livro['autor']) ?></p>
                        <span><?= htmlspecialchars($livro['ano']) ?></span>
                        <?php if ($livro['genero']): ?>
                            <span class="livro-genero"><?= htmlspecialchars($livro['genero']) ?></span>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

    </main>

</div><!-- fecha .dashboard -->

<!-- Modal Cadastrar Livro -->
<div class="modal-overlay hidden" id="modalLivro">
    <div class="modal">
        <div class="modal-header">
            <h2>Cadastrar livro</h2>
            <button type="button" class="fechar-modal" id="fecharModalLivro">&times;</button>
        </div>
        <form id="formLivro" action="/Front-Biblioteca/livros" method="POST">
            <div class="form-group">
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
                <textarea id="anotacoes" name="anotacoes" placeholder="Escreva suas anotações, frases marcantes ou resumo..." rows="4"></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancelar" id="cancelarModalLivro">Cancelar</button>
                <button type="submit" class="btn-salvar">Salvar livro</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Foto de Perfil -->
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
                <input type="file" name="foto" id="inputFoto" accept="image/jpg, image/jpeg, image/png" style="display: none">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancelar" id="cancelarModalFoto">Cancelar</button>
                <button type="submit" class="btn-salvar">Salvar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detalhes do Livro -->
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

<script src="/Front-Biblioteca/public/js/popup.js"></script>
<script src="/Front-Biblioteca/public/js/inatividade.js"></script>
<script src="/Front-Biblioteca/public/js/home.js"></script>
<script src="/Front-Biblioteca/public/Models/home.js"></script>

<?php if ($flash): ?>
    <script>
        mostrarPopup(
            "<?= htmlspecialchars($flash['mensagem']) ?>",
            "<?= htmlspecialchars($flash['tipo']) ?>"
        );
    </script>
<?php endif; ?>