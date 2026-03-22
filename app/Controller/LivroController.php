<?php

require_once __DIR__ . '/../Utils/flash.php';
require_once __DIR__ . '/../Utils/auth.php';


class LivroController {

    public function criarLivro(): void {

    verifyAuth();

    $titulo = trim($_POST['titulo'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $ano = trim($_POST['ano'] ?? 0);
    $genero = trim($_POST['genero'] ?? '');
    $status = trim($_POST['status'] ?? 'quero_ler');
    $avaliacao = $_POST['avaliacao'] !== '' ? (int) $_POST['avaliacao'] : null;
    $anotacoes = trim($_POST['anotacoes'] ?? '') ? : null;

    $token = $_SESSION['token'] ?? null;
    $uuid = $_SESSION['UUID'] ?? null;

    $ch = curl_init('http://localhost:8080/livros');

    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token,
            'X-User-UUID: ' . $uuid
            
        ],
        CURLOPT_POSTFIELDS => json_encode([
            'titulo' => $titulo,
            'autor' => $autor,
            'ano' => (int) $ano,
            'genero' => $genero,
            'status' => $status,
            'avaliacao' => $avaliacao,
            'anotacoes' => $anotacoes,
        ])
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);


    $data = json_decode($response, true) ?? [];

    if($httpCode === 201) {
        setFlash('Livro criado com sucesso!', 'success');
        header('Location: /Front-Biblioteca/home');
        exit();
    } else {
        setFlash($data['message'] ?? 'Erro ao criar livro. Por favor, preencha todos os campos', 'erro');
        header('Location: /Front-Biblioteca/home');
        exit();

    }

    header('Location: /Front-Biblioteca/home');
    exit();
}
}