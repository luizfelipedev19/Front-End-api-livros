
<?php
if(isset($_GET['error']) && $_GET['error'] === 'session_expired'): ?>
<p>Sessão encerrada. Faça login novamente.</p>
<?php endif; ?>

<?php
require_once __DIR__ . '/../Utils/flash.php';
$flash = getFlash();
?>

<head>
    <link rel="stylesheet" href="/Front-Biblioteca/public/css/style.css">
</head>

    <div id="toast" class="toast hidden"></div>
    
    <section class="container">
        <form action="/Front-Biblioteca/login" method="POST" class="container-form" id="formLogin">
            <h3>Login</h3>
            <div class="campo">
                <div class="campo-input">
                <img src="/Front-Biblioteca/public/img/email.svg" alt="" class="icon">
                <label for="email">Email</label>
                <input type="text" placeholder="Digite seu e-mail" id="email" name="email">
                </div>
            </div>

            <div class="campo">
                <div class="campo-input">
                    <img src="/Front-Biblioteca/public/img/cadeado.png" alt="" class="icon">
                <label for="senha">Senha</label>
                <input type="password" placeholder="Digite a sua senha" id="senha" name="senha">
                </div>
            </div>
            <button class="btnLogin" type="submit">Login</button>

            <div  class="acoes-container">
            <button type="button" class="btnEsqueceu" id="esqueceuSenha" onclick="abrirModalSenha()">Esqueceu a senha? Clique aqui</button>

            <a href="/Front-Biblioteca/register">Cadastre-se aqui</a>
            </div>
        </form>

        <div id="modalRecuperacao" style="display: none;" class="container-recuperar">
                <form action="/Front-Biblioteca/recuperar-senha" method="POST" class="container-form-recuperar">
                    <h3>Recuperar senha</h3>
                    <input type="email" name="email" placeholder="Digite seu e-mail" required>
                    <button type="submit" name="recuperar">Enviar</button>
                </form>
            </div>

        
    </section>

    <script src="/Front-Biblioteca/public/js/login.js"></script>
    <script src="../../public/Models/index.js"></script>
    <script src="/Front-Biblioteca/public/js/popup.js"></script>
    

<?php if ($flash): // mostrar popup ?> 
    <script>
            mostrarPopup(
                "<?=  $flash['mensagem'] ?>",
                "<?=  $flash['tipo'] ?>"
            );
        </script>
<?php endif; ?>
