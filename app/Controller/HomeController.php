<?php
$token = $_SESSION['token'] ?? null;
$UUID = $_SESSION['UUID'] ?? null;

require_once __DIR__ . '/../Utils/auth.php';

if (!$token || !$UUID) {
    header('Location: /Front-Biblioteca/');
    exit();
}

class HomeController {

// essa função eu uso para carregar a home e trazendo os livros que o usuário cadastrou
    public function showHome(){

        verifyAuth(); //aqui verifico a autenticação e a sessão, se tiver expirado ou não tiver token/UUID, redireciona para o login.

        $token = $_SESSION['token'] ?? null;
        $uuid = $_SESSION['UUID'] ?? null;

        $ch = curl_init('http://api_livros_app:80/livros');

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $token,
                'X-User-UUID: ' . $uuid
            ]
        ]);

        $response = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $livros = [];
        $total = 0;


        if($httpCode === 200){
            $data = json_decode($response, true) ?? [];
            $livros = $data['detail']['livros'] ?? [];
            $total = $data['detail']['paginacao']['total'] ?? 0;
        }

        require __DIR__ . '/../Views/home.php';
    }
}