<?php

class AuthController
{
    public function showLogin()
    {
        require __DIR__ . '/../Views/index.php';
    }

    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        if ($email === '' || $senha === '') {
            echo "Preencha todos os campos";
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Email inválido";
            return;
        }

        $uri = 'http://localhost:8080/login';

        $payload = json_encode([
            'email' => $email,
            'senha' => $senha
        ]);

        $ch = curl_init($uri);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_POSTFIELDS => $payload
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            echo "Erro ao conectar com a API";
            curl_close($ch);
            return;
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true) ?? [];


        if ($httpCode === 200) {
            $_SESSION['token'] = $data['access_token'] ?? null;
            $_SESSION['UUID'] = $data['UUID'] ?? null;
            $_SESSION['last_activity'] = time();

            header('Location: /Front-Biblioteca/home');
            exit();
        } else {
            echo htmlspecialchars($data['message'] ?? 'Erro ao fazer login');
        }
    }
}