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
        echo "Preencha todos os campos";
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email inválido";
        return;
    }

    define('API_URL', 'http://localhost:8080');
    
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
        echo "Erro ao conectar com a API";
        return;
    }
    
    $data = json_decode($response, true) ?? [];

    if ($httpCode === 200 || $httpCode === 201) {
        setFlash('Usuário cadastrado com sucesso', 'success');
        header('Location: /Front-Biblioteca/');
        exit();
    } else {
        setFlash('Erro ao cadastrar usuário', 'erro');
        header('Location: /Front-Biblioteca/register');
        exit();
    }
   }
}