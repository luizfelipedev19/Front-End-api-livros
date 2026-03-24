<?php

class LogoutController
{
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /Front-Biblioteca/');
        exit();
    }
}