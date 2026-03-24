<?php // carregar flash
require_once __DIR__ . '/../Utils/flash.php';
$flash = getFlash();
?>

<?php
$nomeUsuario = $_SESSION['nome'] ?? 'Usuário';
$fotoPerfil  = $_SESSION['foto_perfil'] ?? null;
$srcFoto     = $fotoPerfil ? htmlspecialchars
($fotoPerfil) : '/Front-Biblioteca/public/img/avatar.png';
$email = $_SESSION['email'] ?? null;
?>



<head>
    <link rel="stylesheet" href="/Front-Biblioteca/public/css/perfil.css">
</head>

<div id="toast" class="toast hidden"></div>

<div class="dashboard">

    <aside class="sidebar">
        <div class="logo">BM</div>
        <nav class="menu">
            <ul>
                <li><a href="/Front-Biblioteca/home">Home</a></li>
                <li><a href="#">Meus livros</a></li>
                <li><a href="#">Favoritos</a></li>
                <li><a href="#">Citações</a></li>
                <li><a href="/Front-Biblioteca/perfil">Perfil</a></li>
            </ul>
        </nav>
    </aside>
    <div id="menuOverlay" class="menu-overlay"></div>

    <main class="content">
        <header class="topbar">
            <h1>BookManager</h1>
            <div class="user-area">
                <span><?= htmlspecialchars($nomeUsuario) ?></span>
                <img src="<?= $srcFoto ?>" alt="Foto do usuário" id="fotoPerfil" title="Clique para alterar a sua foto">
                <a href="/Front-Biblioteca/logout">Sair</a>
            </div>
            
        </header>
<section class="perfil-container">
                
                <div class="perfil-card">
                    <div class="perfil-foto-wrapper">
                        <img class="fotoDePerfil" src="<?= $srcFoto ?>" alt="Foto do usuário" id="fotoPerfilCard">
                        <button type="button" id="btnAlterarFoto">Altera foto</button>
                    </div>

                    <div class="perfil-info">
                        <h2><?= htmlspecialchars($nomeUsuario) ?></h2>
                        <p><?= htmlspecialchars($email ?? 'Email não informado') ?></p>
                    </div>

                </div>
                <div class="perfil-stats">
                    <div class="card">
                        <p>Total de livros</p>
                        <h2><?= $total ?? 0 ?></h2>
                    </div>

                    <div class="card">
                        <p>Lendo</p>
                        <h2><?= $lendo ?? 0 ?></h2>
                    </div>
                    <div class="card">
                        <p>Lidos</p>
                        <h2><?= $lidos ?? 0 ?></h2>
                    </div>
                    <div class="card">
                        <p>Quero ler</p>
                        <h2><?= $quero_ler ?? 0 ?></h2>
                    </div>
                </div>
</section>
        

    </main>

</div><!-- fecha .dashboard -->



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


<script src="/Front-Biblioteca/public/js/popup.js"></script>
<script src="/Front-Biblioteca/public/js/inatividade.js"></script>
<script src="/Front-Biblioteca/public/js/home.js"></script>
<script src="/Front-Biblioteca/public/Controller/perfil.js"></script>

<?php if ($flash): ?>
    <script>
        mostrarPopup(
            "<?= htmlspecialchars($flash['mensagem']) ?>",
            "<?= htmlspecialchars($flash['tipo']) ?>"
        );
    </script>
<?php endif; ?>