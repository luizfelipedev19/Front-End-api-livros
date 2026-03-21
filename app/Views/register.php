
    <div id="toast" class="toast hidden"></div>
    <section class="container">
        
        <form action="/Front-Biblioteca/register" method="POST" class="container-form" id="formRegister">

            <h3>Cadastrar</h3>
            <div class="campo">
                <div class="campo-input">
                <img src="../img/person.svg" alt="" class="icon">
                <label for="nome">Nome</label>
                <input type="text" placeholder="Digite o seu nome" name="nome" id="nome">
                </div>
            </div>

            <div class="campo">
                <div class="campo-input">
                <img src="../img/email.svg" alt="" class="icon">
                <label for="email">Email</label>
                <input type="text" placeholder="Digite seu e-mail" name="email" id="email">
                </div>
            </div>

            <div class="campo">
                <div class="campo-input">
                    <img src="../img/cadeado.png" alt="" class="icon">
                <label for="senha">Senha</label>
                <input type="password" placeholder="Digite a sua senha" id="senha" name="senha">
                </div>
            </div>
            <button type="submit">Cadastrar</button>
            <a href="/Front-Biblioteca/showLogin">Ja tem cadastro? Entre aqui</a>
        </form>

    </section>

    <script src="../js/register.js">
    </script>
