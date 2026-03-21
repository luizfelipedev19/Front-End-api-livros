
<?php
if(isset($_GET['error']) && $_GET['error'] === 'session_expired'): ?>
<p>Sessão encerrada. Faça login novamente.</p>
<?php endif; ?>


    <div id="toast" class="toast hidden"></div>
    
    <section class="container">
        <form action="/Front-Biblioteca/login" method="POST" class="container-form" id="formLogin">
            <h3>Login</h3>
            <div class="campo">
                <div class="campo-input">
                <img src="../img/email.svg" alt="" class="icon">
                <label for="email">Email</label>
                <input type="text" placeholder="Digite seu e-mail" id="email" name="email">
                </div>
            </div>

            <div class="campo">
                <div class="campo-input">
                    <img src="../img/cadeado.png" alt="" class="icon">
                <label for="senha">Senha</label>
                <input type="password" placeholder="Digite a sua senha" id="senha" name="senha">
                </div>
            </div>
            <button type="submit">Login</button>
            <a href="/Front-Biblioteca/register" class="acoes">Cadastre-se aqui</a>
            
        </form>
    </section>

    <script src="../../public/js/login.js"></script>
