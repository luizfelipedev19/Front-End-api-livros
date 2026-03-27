<?php
$token = $_GET['token'] ?? '';
?>

<head>
    <link rel="stylesheet" href="/public//css/redefinir-senha.css">
</head>
<div id="toast" class="toast hidden"></div>

<div class="container">
<form action="/Front-Biblioteca/redefinir-senha" method="POST" class="container-form">
    <h3>Mudar senha</h3>
    <input type="hidden" name="token" value="<?= $token ?>">
    <input type="password" name="senha" placeholder="Digite a nova senha" required>

    <button type="submit">Redefinir senha</button>
</form>
</div>