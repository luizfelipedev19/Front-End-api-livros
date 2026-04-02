<?php

require_once __DIR__ . '/../Utils/flash.php';

class SenhaController{
    public function mostrarTelaRedefinir(){
        require_once __DIR__ . '../../Views/redefinir-senha.php';
    }


//responsavel por enviar o e-mail
    public function solicitarRecuperacao() {
    if (!isset($_POST['email'])) {
        return;
    }

    $data = [
        "email" => $_POST['email']
    ];

    $start = microtime(true);

    $ch = curl_init("http://api_livros_app:80/recuperar-senha");

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10
    ]);

    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    $curlErrno = curl_errno($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $duration = round((microtime(true) - $start) * 1000, 2); // ms

    // Log estruturado
    error_log(json_encode([
        'evento' => 'recuperacao_senha',
        'email' => $data['email'],
        'http_code' => $httpCode,
        'curl_errno' => $curlErrno,
        'curl_error' => $curlError,
        'tempo_ms' => $duration,
        'response_raw' => substr($response, 0, 500) // evita log gigante
    ]));

    // Falha de conexão
    if ($response === false) {
        setFlash("Erro ao conectar com o servidor", "erro");
        header("Location: /Front-Biblioteca/?erro=1");
        exit;
    }

    // Decodifica JSON
    $res = json_decode($response, true);

    // JSON inválido
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log(json_encode([
            'erro' => 'json_invalido',
            'response' => $response,
            'json_error' => json_last_error_msg()
        ]));

        setFlash("Erro inesperado no servidor", "erro");
        header("Location: /Front-Biblioteca/?erro=1");
        exit;
    }

    // Sucesso
    if (isset($res['success']) && $res['success']) {
        setFlash("Se o email estiver cadastrado, você receberá as instruções para redefinir sua senha", "success");
        header("Location: /Front-Biblioteca/?msg=email_enviado");
    } else {
        error_log(json_encode([
            'erro' => 'api_retorno_false',
            'resposta' => $res
        ]));

        setFlash("Não foi possível processar a solicitação", "erro");
        header("Location: /Front-Biblioteca/redefinir-senha?erro=1");
    }

    exit;
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