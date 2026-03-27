<?php

require_once __DIR__ . '/../Utils/flash.php';

class SenhaController{
    public function mostrarTelaRedefinir(){
        require_once __DIR__ . '../../Views/redefinir-senha.php';
    }


//responsavel por enviar o e-mail
    public function solicitarRecuperacao() {
        if(isset($_POST['email'])){
            $data = [
                "email" => $_POST['email']
            ];

            $ch = curl_init("http://api_livros_app:80/recuperar-senha");

            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json',
                'Accept: application/json'],
                CURLOPT_POSTFIELDS => json_encode($data)
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $res = json_decode($response, true);

            if($res && isset($res['success']) && $res['success']){
                setFlash("E-mail enviado com sucesso","success");
                header("Location: /Front-Biblioteca/?msg=email_enviado");
            } else {
                setFlash("Se for o e-mail de cadastro, verifique a caixa de email", "Erro");
                header("Location: /Front-Biblioteca/?erro=1");
            }
            exit;
        }
    }

    public function redefinirSenha() {
        if(isset($_POST['senha'], $_POST['token'])) {
            $data = [
                "token" => $_POST['token'],
                "senha" => $_POST['senha']
            ];

            $ch = curl_init("http://api_livros_app:80/redefinir-senha");

            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                'Accept: application/json'
                ],
                CURLOPT_POSTFIELDS => json_encode($data)
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $res = json_decode($response, true);

            if(isset($res['success']) && $res['success']){
                setFlash("Senha alterada com sucesso", "success");
                header("Location: /Front-Biblioteca/?msg=senha_alterada");
            } else {
                setFlash("Não foi possível alterar a senha", "erro");
                header("Location: /Front-Biblioteca/redefinir-senha?erro=1");
            }

            exit;
        }
    }
}