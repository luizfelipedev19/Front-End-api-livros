<?php

//função em php responsavel por exibir os poups de mensagem na tela
function setFlash (string $mensagem, string $tipo ='erro'): void {
    $_SESSION['flash'] = [
        'mensagem' => $mensagem,
        'tipo' => $tipo
    ];
}

function getFlash(): ?array {
    if(!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}