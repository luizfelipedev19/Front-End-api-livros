<?php
$token = $_SESSION['token'] ?? null;
$uuid = $_SESSION['UUID'] ?? null;

require_once __DIR__ . '/../Utils/auth.php';

if (!$token || !$uuid) {
    header('Location: /Front-Biblioteca/');
    exit();
}

class PerfilController {

    public function showPerfil(){
        verifyAuth();


        $token = $_SESSION['token'] ?? null;
        $uuid = $_SESSION['UUID'] ?? null;

        $ch = curl_init('http://api_livros_app:80/livros');

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => [
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
            $livros = $data['livros'] ??  [];
            $total = $data['paginacao'] ['total'] ?? 0;


            $lendo    = count(array_filter($livros, fn($l) => $l['status'] === 'lendo'));
            $lidos    = count(array_filter($livros, fn($l) => $l['status'] === 'lido'));
            $quero_ler = count(array_filter($livros, fn($l) => $l['status'] === 'quero_ler'));
        }

        require __DIR__ . '/../Views/perfil.php';
    }
}
