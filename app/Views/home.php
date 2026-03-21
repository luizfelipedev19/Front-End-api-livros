
<div id="toast" class="toast hidden"></div>
    <div class="dashboard">

        <aside class="sidebar">
            <div class="logo">BM</div>

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
                    <span>Luiz Felipe</span>
                    <img src="img/user.png" alt="Foto do usuário">
                    <a href="#">Sair</a>
                </div>
            </header>

            <section class="cards-resumo">
                <div class="card">
                    <p>Total de livros</p>
                    <h2 id="totalDeLivros">0</h2>
                </div>

                <div class="card">
                    <p>Favoritos</p>
                    <h2 id="totalDeFavoritos">0</h2>
                </div>

                <div class="card">
                    <p>Último cadastrado</p>
                    <h2 id="ultimoLivro">Clean Code</h2>
                </div>
            </section>

            <section class="acoes-filtros">
                <button type="submit" class="btn-cadastrar" id="abrirModalLivro">+ Cadastrar novo livro</button>

                <div class="busca-filtros">
                    <input type="text" placeholder="Buscar livro...">

                    <div class="filtros">
                        <select>
                            <option>Todos os autores</option>
                        </select>

                        <select>
                            <option>Todos os anos</option>
                        </select>

                        <select>
                            <option>Todos os status</option>
                        </select>
                    </div>
                </div>
            </section>

            <section class="lista-livros">
                <article class="livro-card">
                    <img src="img/clean-code.jpg" alt="Capa do livro Clean Code">
                    <h3>Clean Code</h3>
                    <p>Robert Martin</p>
                    <span>2008</span>
                </article>

                <article class="livro-card">
                    <img src="img/arquitetura-limpa.jpg" alt="Capa do livro Arquitetura Limpa">
                    <h3>Arquitetura Limpa</h3>
                    <p>Robert Martin</p>
                    <span>2017</span>
                </article>

                <article class="livro-card">
                    <img src="img/arquitetura-limpa.jpg" alt="Capa do livro Arquitetura Limpa">
                    <h3>Arquitetura Limpa</h3>
                    <p>Robert Martin</p>
                    <span>2017</span>
                </article>
            </section>

        </main>

<div class="modal-overlay hidden" id="modalLivro">
    <div class="modal">
        <div class="modal-header">
            <h2>Cadastrar livro</h2>
            <button type="submit" class="fechar-modal" id="fecharModalLivro">&times;</button>
        </div>

        <form id="formLivro">
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" placeholder="Digite o título do livro" required>
            </div>

            <div class="form-group">
                <label for="autor">Autor</label>
                <input type="text" id="autor" name="autor" placeholder="Digite o autor" required>
            </div>

            <div class="form-group">
                <label for="ano">Ano</label>
                <input type="number" id="ano" name="ano" placeholder="Digite o ano" required>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancelar" id="cancelarModalLivro">Cancelar</button>
                <button type="submit" class="btn-salvar">Salvar livro</button>
            </div>
        </form>
    </div>
</div>
    </div>

    <script src="/Front-Biblioteca/public/Models/home.js"></script>
    <script src="/Front-Biblioteca/public/js/inatividade.js"></script>

