<?php

class LogoutController
{

//destroi a sessão e redireciona para a tela de login se caso o usuário clicar em "sair"
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /Front-Biblioteca/');
        exit();
    }
}