<?php

/*
|--------------------------------------------------------------------------
| UTILITÁRIO DE FLASH MESSAGE
|--------------------------------------------------------------------------
| Esse arquivo é responsável por armazenar mensagens temporárias
| na sessão para exibir feedback ao usuário depois de redirecionamentos.
|
| Exemplo:
| - "Login realizado com sucesso!"
| - "Email inválido"
| - "Erro ao conectar com a API"
*/
require_once __DIR__ . '/../Utils/flash.php';

/*
|--------------------------------------------------------------------------
| AUTH CONTROLLER
|--------------------------------------------------------------------------
| Esse controller é responsável pelo fluxo de login do sistema.
|
| Responsabilidades principais:
| - exibir a tela de login
| - validar os dados enviados pelo formulário
| - encaminhar a requisição para a API externa
| - salvar os dados de autenticação na sessão
| - responder com JSON quando o login vier via fetch/AJAX
| - ou redirecionar normalmente quando vier de submit tradicional
*/
class AuthController
{
    /*
     * ---------------------------------------------------------
     * EXIBIR TELA DE LOGIN
     * ---------------------------------------------------------
     * Apenas carrega a view principal de login.
     */
    public function showLogin()
    {
        require __DIR__ . '/../Views/index.php';
    }

    /*
     * ---------------------------------------------------------
     * VERIFICAR SE A REQUISIÇÃO VEIO VIA AJAX / FETCH
     * ---------------------------------------------------------
     * Esse método é usado para decidir se o retorno será:
     * - JSON (quando a chamada vier do JavaScript)
     * - redirecionamento com flash message (quando vier de submit tradicional)
     */
    private function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /*
     * ---------------------------------------------------------
     * RESPOSTA JSON PADRONIZADA
     * ---------------------------------------------------------
     * Facilita o retorno para o front-end quando a requisição
     * é feita via fetch.
     *
     * Parâmetros:
     * - $data: conteúdo da resposta
     * - $statusCode: código HTTP da resposta
     */
    private function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();
    }

    /*
     * ---------------------------------------------------------
     * PROCESSAR LOGIN
     * ---------------------------------------------------------
     * Fluxo resumido:
     * 1. Garante que a sessão esteja iniciada
     * 2. Lê os dados enviados pelo formulário
     * 3. Faz validações básicas
     * 4. Envia os dados para a API de login
     * 5. Trata resposta da API
     * 6. Se sucesso: salva sessão e redireciona / responde JSON
     * 7. Se erro: devolve mensagem apropriada
     */
    public function login()
    {
        /*
         * Garante que a sessão esteja aberta antes de gravar
         * token, UUID e demais dados do usuário.
         */
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        /*
         * Lê os campos enviados pelo formulário.
         * O trim ajuda a evitar espaços desnecessários no início/fim.
         */
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        /*
         * Validação básica:
         * não permite seguir se email ou senha vierem vazios.
         */
        if ($email === '' || $senha === '') {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse([
                    'success' => false,
                    'mensagem' => 'Preencha todos os campos'
                ], 422);
            }

            setFlash('Preencha todos os campos', 'erro');
            header('Location: /Front-Biblioteca/');
            exit();
        }

        /*
         * Validação do formato do email.
         */
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse([
                    'success' => false,
                    'mensagem' => 'Email inválido'
                ], 422);
            }

            setFlash('Email inválido', 'erro');
            header('Location: /Front-Biblioteca/');
            exit();
        }

        /*
         * Endpoint da API externa responsável pelo login.
         */
        $uri = 'http://api_livros_app:80/login';

        /*
         * Monta o payload que será enviado para a API.
         */
        $payload = json_encode([
            'email' => $email,
            'senha' => $senha
        ]);

        /*
         * Inicializa a chamada HTTP para a API usando cURL.
         */
        $ch = curl_init($uri);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_POSTFIELDS => $payload
        ]);

        /*
         * Executa a requisição para a API.
         */
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        /*
         * Se a API não respondeu corretamente, tratamos como erro
         * de comunicação.
         */
        if ($response === false) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse([
                    'success' => false,
                    'mensagem' => 'Erro ao conectar com a API',
                    'erro' => $curlError
                ], 500);
            }

            setFlash('Erro ao conectar com a API', 'erro');
            header('Location: /Front-Biblioteca/');
            exit();
        }

        /*
         * Decodifica a resposta JSON da API.
         *
         * Estrutura esperada:
         * [
         *   "success" => true/false,
         *   "detail" => [
         *       "mensagem" => "...",
         *       "access_token" => "...",
         *       "UUID" => "...",
         *       "nome" => "...",
         *       "email" => "...",
         *       "foto_perfil" => ...
         *   ]
         * ]
         */
        $data = json_decode($response, true) ?? [];
        $detail = $data['detail'] ?? [];

        /*
         * Se o login foi bem-sucedido, salvamos os dados principais
         * do usuário na sessão.
         */
        if (($data['success'] ?? false) === true) {
            /*
             * Regenera o ID da sessão para aumentar a segurança
             * após o login.
             */
            session_regenerate_id(true);

            /*
             * Dados principais usados no restante do sistema:
             * - token de autenticação
             * - UUID do usuário
             * - nome
             * - email
             * - foto de perfil
             * - controle de inatividade
             */
            $_SESSION['token'] = $detail['access_token'] ?? null;
            $_SESSION['UUID'] = $detail['UUID'] ?? null;
            $_SESSION['last_activity'] = time();
            $_SESSION['nome'] = $detail['nome'] ?? null;
            $_SESSION['foto_perfil'] = $detail['foto_perfil'] ?? null;
            $_SESSION['email'] = $detail['email'] ?? null;

            /*
             * Fecha a escrita da sessão para garantir que os dados
             * sejam persistidos antes do próximo redirecionamento.
             */
            session_write_close();

            /*
             * Resposta para chamadas AJAX / fetch.
             */
            if ($this->isAjaxRequest()) {
                $this->jsonResponse([
                    'success' => true,
                    'mensagem' => $detail['mensagem'] ?? 'Login realizado com sucesso!',
                    'redirect' => '/Front-Biblioteca/home'
                ], 200);
            }

            /*
             * Resposta para submit tradicional do formulário.
             */
            setFlash($detail['mensagem'] ?? 'Login realizado com sucesso!', 'success');
            header('Location: /Front-Biblioteca/home');
            exit();
        }

        /*
         * Se o login falhou, tentamos recuperar a mensagem da API.
         * Caso não venha nada, usamos uma mensagem padrão.
         */
        $mensagem = $detail['mensagem'] ?? $data['mensagem'] ?? 'Email ou senha inválidos';

        /*
         * Retorno de erro para chamadas AJAX / fetch.
         */
        if ($this->isAjaxRequest()) {
            $this->jsonResponse([
                'success' => false,
                'mensagem' => $mensagem
            ], $httpCode > 0 ? $httpCode : 401);
        }

        /*
         * Retorno de erro para submit tradicional.
         */
        setFlash($mensagem, 'erro');
        header('Location: /Front-Biblioteca/');
        exit();
    }
}