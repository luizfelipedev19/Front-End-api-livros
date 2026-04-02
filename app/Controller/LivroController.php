<?php

require_once __DIR__ . '/../Utils/flash.php';
require_once __DIR__ . '/../Utils/auth.php';


class LivroController {

public function salvarLivro(): void {
verifyAuth();

$idLivro = $_POST['id_livro'] ?? null;

$titulo = trim($_POST['titulo'] ?? '');
$autor = trim($_POST['autor'] ?? '');
$ano = trim($_POST['ano'] ?? 0);
$genero = isset($_POST['genero']) && $_POST['genero'] !== '' 
    ? trim($_POST['genero']) 
    : null;
$status = trim($_POST['status'] ?? 'quero_ler');
$avaliacao = $_POST['avaliacao'] !== '' ? (int) $_POST['avaliacao'] : null;
$anotacoes = isset($_POST['anotacoes']) && $_POST['anotacoes'] !== ''
    ? trim($_POST['anotacoes'])
    : null;


$token = $_SESSION['token'] ?? null;
$uuid = $_SESSION['UUID'] ?? null;

if(!$token || !$uuid){
    setFlash('Usuário não autenticado', 'erro');
    header('Location: /Front-Biblioteca/');
    exit();
}

$isEdit = !empty($idLivro);

$url = $isEdit 
? "http://api_livros_app:80/livro/editar"
: "http://api_livros_app:80/livros";

$method = $isEdit ? 'PUT' : 'POST';

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $token,
        'X-User-UUID: ' . $uuid
    ],
    CURLOPT_POSTFIELDS => json_encode(
    array_filter([
        'id_livro'  => $idLivro ?: null,
        'titulo'    => $titulo,
        'autor'     => $autor,
        'ano'       => (int) $ano,
        'genero'    => $genero,
        'status'    => $status,
        'avaliacao' => $avaliacao,
        'anotacoes' => $anotacoes
    ], fn($v) => !($v === null && $isEdit === false && /* campo é id */ false))
)
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($response, true) ?? [];

if($httpCode === 200 || $httpCode === 201){
    setFlash($isEdit ? 'Livro editado com sucesso' : 'Livro cadastrado com sucesso', 'success');
} else {
    setFlash($data['mensagem'] ?? 'Erro ao salvar o livro', 'erro');
}
header('Location: /Front-Biblioteca/home');
exit();
}

public function deletarLivro(): void {

//função que consome o endpoit da api que serve para deletar os livros cadastrados
    verifyAuth();

    $idLivro = $_POST['id_livro'] ?? null;

    if(!$idLivro){
        setFlash('Id do livro não encontrado', 'erro');
        header('location: /Front-Biblioteca/home');
        exit();
    }


    $token = $_SESSION['token'] ?? null;
    $uuid = $_SESSION['UUID'] ?? null;

    $ch = curl_init('http://api_livros_app:80/livro/deletar');


    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token,
            'X-User-UUID: ' . $uuid
        ],
        CURLOPT_POSTFIELDS => json_encode(['id_livro' => $idLivro])
    ]);

    $responseDeletar = curl_exec($ch);
    $httpCodeDeletar = curl_getinfo($ch, CURLINFO_HTTP_CODE);


    $dataDeletar = json_encode($responseDeletar, true) ?? [];

    if($httpCodeDeletar === 200){
        setFlash('Livro deletado com sucesso', 'success');
    } else {
        setFlash($dataDeletar['mensagem'] ?? 'Não foi possível deletar o livro', 'erro');
    }

    header('location: /Front-Biblioteca/home');
    exit();

    //fim da função
}
}