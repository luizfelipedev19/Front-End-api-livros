<?php

require_once __DIR__ . '/../Utils/flash.php';

class RegisterController {
   public function showRegister(){
    require __DIR__ . '/../Views/register.php';
   }

   public function registerUser(){
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if($nome === '' || $email === '' || $senha === ''){
        setFlash('Preencha todos os campos', 'erro');
        header('Location: /Front-Biblioteca/register');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setFlash('Email inválido', 'erro');
        header('Location: /Front-Biblioteca/register');
        exit();
    }
    

    define('API_URL', 'http://api_livros_app:80');
    
    $uri = API_URL . '/register';


    $payload = json_encode([
        'nome' => $nome,
        'email' => $email,
        'senha' => $senha
    ]);

    $ch = curl_init($uri);

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => $payload
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response == false){
        setFlash('Erro ao conectar com a API', 'erro');
        header('Location: /Front-Biblioteca/register');
        exit();
    }
    
    $data = json_decode($response, true) ?? [];

if (($data['success'] ?? false) === true) {
    setFlash('Usuário cadastrado com sucesso', 'success');
    header('Location: /Front-Biblioteca/');
    exit();

} elseif ($httpCode === 409) {
    setFlash($data['mensagem'] ?? 'Não foi possível cadastrar. Este e-mail já está sendo utilizado.', 'erro');

} else {
    setFlash($data['mensagem'] ?? 'Erro ao cadastrar usuário. Tente novamente mais tarde.', 'erro');
}

header('Location: /Front-Biblioteca/register');
exit();

}
}