<?php

function verifyAuth(){
    if(!isset($_SESSION['token']) || !isset($_SESSION['UUID'])){
        header('Location: /Front-Biblioteca/');
        exit();
    }

    $tempoLimite = 900; //coloquei 15 minutos para testar.

    if(isset($_SESSION['last_activity'])){
        $tempoInativo = time() - $_SESSION['last_activity'];

        if($tempoInativo > $tempoLimite){
            session_unset();
            session_destroy();
            header('Location: /Front-Biblioteca/?session_expired=true');
            exit();
        }
    }
    $_SESSION['last_activity'] = time();
}