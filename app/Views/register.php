<?php
require_once __DIR__ . '/../Utils/flash.php';
$flash = getFlash();
?>

<head>
    <link rel="stylesheet" href="/Front-Biblioteca/public/css/register.css">
</head>

<div id="div" class="toast hidden"></div>






<section class="container">

   
    <div class="card-wrapper">


        <div class="container-card">
            <h1><strong>BookManager</strong>, o seu gerenciador de livros.</h1>
             <p class="texto2">Faça seu cadastro agora e tenha suas metas de leitura diárias batidas</p>
        </div>


        <div class="card-bg"></div>


        <form action="/Front-Biblioteca/register" method="POST" class="container-form" id="formRegister">

            <h3>Cadastrar</h3>

            <div class="campo">
                <div class="campo-input">
                    <img src="/Front-Biblioteca/public/img/person.svg" alt="" class="icon">
                    <label for="nome">Nome</label>
                    <input type="text" placeholder="Digite o seu nome" name="nome" id="nome">
                </div>
            </div>

            <div class="campo">
                <div class="campo-input">
                    <img src="/Front-Biblioteca/public/img/email.svg" alt="" class="icon">
                    <label for="email">Email</label>
                    <input type="text" placeholder="Digite seu e-mail" name="email" id="email">
                </div>
            </div>

            <div class="campo">
                <div class="campo-input">
                    <img src="/Front-Biblioteca/public/img/cadeado.png" alt="" class="icon">
                    <label for="senha">Senha</label>
                    <input type="password" placeholder="Digite a sua senha" id="senha" name="senha">
                </div>
            </div>
            <button type="submit">Cadastrar</button>
            <a href="/Front-Biblioteca/">Ja tem cadastro? Entre aqui</a>
        </form>
    </div>
</section>

<script src="../js/register.js">
</script>
<script src="/Front-Biblioteca/public/js/popup.js"></script>


<?php if ($flash): // mostrar popup 
?>
    <script>
        mostrarPopup(
            "<?= $flash['mensagem'] ?>",
            "<?= $flash['tipo'] ?>"
        );
    </script>
<?php endif; ?>