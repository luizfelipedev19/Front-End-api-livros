<?php

require_once __DIR__ . '/../Utils/flash.php';
require_once __DIR__ . '/../Utils/auth.php';


class LivroController {

// criar livro ->
    public function criarLivro(): void {

    verifyAuth();

    $titulo = trim($_POST['titulo'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $ano = trim($_POST['ano'] ?? 0);
    $genero = isset($_POST['genero']) && trim($_POST['genero']) !== '' 
    ? trim($_POST['genero']) 
    : null;
    $status = trim($_POST['status'] ?? 'quero_ler');
    $avaliacao = $_POST['avaliacao'] !== '' ? (int) $_POST['avaliacao'] : null;
    $anotacoes = isset($_POST['anotacoes']) && trim($_POST['anotacoes']) !== ''
    ? trim($_POST['anotacoes'])
    : null;

    $token = $_SESSION['token'] ?? null;
    $uuid = $_SESSION['UUID'] ?? null;

    $ch = curl_init('http://api_livros_app:80/livros');

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
    // fim da função de criar livro 
}



public function editarLivro(): void{
    // função para editar livros -> edpoint da api
    verifyAuth();

    $idLivro = $_GET['id'] ?? null;

    if(!$idLivro){
        setFlash('Id do Livro não informado.', 'erro');
        header('location: /Front-Biblioteca/home');
        exit();
    }

    $titulo = trim($_POST['titulo'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $ano = trim($_POST['ano'] ?? 0);
    $genero = isset($_POST['genero']) && trim($_POST['genero']) !== '' 
    ? trim($_POST['genero']) 
    : null;
    $status = trim($_POST['status'] ?? 'quero_ler');
    $avaliacao = $_POST['avaliacao'] !== '' ? (int) $_POST['avaliacao'] : null;
    $anotacoes = isset($_POST['anotacoes']) && trim($_POST['anotacoes']) !== ''
    ? trim($_POST['anotacoes'])
    : null;

    $token = $_SESSION['token'] ?? null;
    $uuid = $_SESSION['UUID'] ?? null;

    $ch = curl_init("http://api_livros_app:80/livro?id=" . $idLivro);


    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer '. $token,
            'X-User-UUID: ' . $uuid
        ],
        CURLOPT_POSTFIELDS => json_encode([
            'titulo' => $titulo,
            'autor' => $autor,
            'ano' => $ano,
            'genero' => $genero,
            'status' => $status,
            'avaliacao' => $avaliacao,
            'anotacoes' => $anotacoes
        ])
    ]);

    $responseEditar = curl_exec($ch);
    $httpCodeEditar = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);


    $dataEditar = json_decode($responseEditar, true) ?? [];

    if($httpCodeEditar === 200){
        setFlash('Livro atualizado com sucesso', 'success');
    } else{
        setFlash($dataEditar['mensagem'] ?? 'Nao foi possível atualizar o livro', 'erro');;
    }

    header('location: /Front-Biblioteca/home');
    exit();

    //fim da função de editar
}

public function deletarLivro(): void {

//função que consome o endpoit da api que serve para deletar os livros cadastrados
    verifyAuth();

    $idLivro = $_GET['id'] ?? null;

    if(!$idLivro){
        setFlash('Id do livro não encontrado', 'erro');
        header('location: /Front-Biblioteca/home');
        exit();
    }


    $token = $_SESSION['token'] ?? null;
    $uuid = $_SESSION['UUID'] ?? null;

    $ch = curl_init('http://api_livros_app:80/livro?id=' . $idLivro);


    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token,
            'X-User-UUID: ' . $uuid
        ] 
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