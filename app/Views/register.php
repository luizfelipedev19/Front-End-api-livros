<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/register.css">
    <title>Biblioteca</title>
</head>
<body>
    <div id="toast" class="toast hidden"></div>
    <section class="container">
        
        <form  class="container-form" id="formRegister">

            <h3>Cadastrar</h3>
            <div class="campo">
                <div class="campo-input">
                <img src="../img/person.svg" alt="" class="icon">
                <label for="nome">Nome</label>
                <input type="text" placeholder="Digite o seu nome" id="nome">
                </div>
            </div>

            <div class="campo">
                <div class="campo-input">
                <img src="../img/email.svg" alt="" class="icon">
                <label for="email">Email</label>
                <input type="text" placeholder="Digite seu e-mail" id="email">
                </div>
            </div>

            <div class="campo">
                <div class="campo-input">
                    <img src="../img/cadeado.png" alt="" class="icon">
                <label for="senha">Senha</label>
                <input type="password" placeholder="Digite a sua senha" id="senha">
                </div>
            </div>
            <button type="submit">Cadastrar</button>
            <a href="index.html">Ja tem cadastro? Entre aqui</a>
        </form>

    </section>

    <script src="../js/register.js">
    </script>
</body>
</html>