<?php
$token = $_SESSION['token'] ?? null;
$UUID = $_SESSION['UUID'] ?? null;

require_once __DIR__ . '/../Utils/auth.php';

if (!$token || !$UUID) {
    header('Location: /Front-Biblioteca/');
    exit();
}

class HomeController {
    public function showHome(){

        verifyAuth(); //aqui verifico a autenticação e a sessão, se tiver expirado ou não tiver token/UUID, redireciona para o login.

        require __DIR__ . '/../Views/home.php';
    }
}