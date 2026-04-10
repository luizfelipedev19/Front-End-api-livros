<?php

require_once __DIR__ . '/../Utils/flash.php';

class RegisterController
{
    public function showRegister()
    {
        require __DIR__ . '/../Views/register.php';
    }

    private function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();
    }

    public function registerUser()
    {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        if ($nome === '' || $email === '' || $senha === '') {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse([
                    'success' => false,
                    'mensagem' => 'Preencha todos os campos'
                ], 422);
            }

            setFlash('Preencha todos os campos', 'erro');
            header('Location: /Front-Biblioteca/register');
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse([
                    'success' => false,
                    'mensagem' => 'Email inválido'
                ], 422);
            }

            setFlash('Email inválido', 'erro');
            header('Location: /Front-Biblioteca/register');
            exit();
        }

        if (!defined('API_URL')) {
            define('API_URL', 'http://api_livros_app:80');
        }

        $uri = API_URL . '/register';

        $payload = json_encode([
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha
        ]);

        $ch = curl_init($uri);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $payload
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse([
                    'success' => false,
                    'mensagem' => 'Erro ao conectar com a API',
                    'erro' => $curlError
                ], 500);
            }

            setFlash('Erro ao conectar com a API', 'erro');
            header('Location: /Front-Biblioteca/register');
            exit();
        }

        $data = json_decode($response, true) ?? [];

        if (($data['success'] ?? false) === true) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse([
                    'success' => true,
                    'mensagem' => 'Usuário cadastrado com sucesso',
                    'redirect' => '/Front-Biblioteca/'
                ], 200);
            }

            setFlash('Usuário cadastrado com sucesso', 'success');
            header('Location: /Front-Biblioteca/');
            exit();
        }

        if ($httpCode === 409) {
            $mensagem = $data['mensagem'] ?? 'Não foi possível cadastrar. Este e-mail já está sendo utilizado.';

            if ($this->isAjaxRequest()) {
                $this->jsonResponse([
                    'success' => false,
                    'mensagem' => $mensagem
                ], 409);
            }

            setFlash($mensagem, 'erro');
            header('Location: /Front-Biblioteca/register');
            exit();
        }

        $mensagem = $data['mensagem'] ?? 'Erro ao cadastrar usuário. Tente novamente mais tarde.';

        if ($this->isAjaxRequest()) {
            $this->jsonResponse([
                'success' => false,
                'mensagem' => $mensagem
            ], $httpCode > 0 ? $httpCode : 500);
        }

        setFlash($mensagem, 'erro');
        header('Location: /Front-Biblioteca/register');
        exit();
    }
}