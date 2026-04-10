<?php

require_once __DIR__ . '/../Utils/auth.php';

class HomeController
{
    private function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();
    }

    private function buscarLivrosApi(): array
    {
        $token = $_SESSION['token'] ?? null;
        $uuid  = $_SESSION['UUID'] ?? null;

        $ch = curl_init('http://api_livros_app:80/livros');

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $token,
                'X-User-UUID: ' . $uuid
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return [
                'success' => false,
                'mensagem' => 'Erro ao conectar com a API',
                'erro' => $curlError,
                'httpCode' => 500
            ];
        }

        $data = json_decode($response, true) ?? [];

        if ($httpCode !== 200) {
            return [
                'success' => false,
                'mensagem' => $data['mensagem'] ?? 'Erro ao buscar livros',
                'data' => $data,
                'httpCode' => $httpCode > 0 ? $httpCode : 500
            ];
        }

        return [
            'success' => true,
            'data' => $data,
            'httpCode' => 200
        ];
    }

    // carrega a página home
    public function showHome(): void
    {
        verifyAuth();

        $resultado = $this->buscarLivrosApi();

        $livros = [];
        $total = 0;

        if ($resultado['success']) {
            $apiData = $resultado['data'];
            $livros = $apiData['detail']['livros'] ?? [];
            $total  = $apiData['detail']['paginacao']['total'] ?? 0;
        }

        require __DIR__ . '/../Views/home.php';
    }

    // endpoint para o JS buscar os livros via fetch
    public function listarLivros(): void
    {
        verifyAuth();

        $resultado = $this->buscarLivrosApi();

        if (!$resultado['success']) {
            $this->jsonResponse([
                'success' => false,
                'mensagem' => $resultado['mensagem'] ?? 'Erro ao buscar livros'
            ], $resultado['httpCode'] ?? 500);
        }

        $apiData = $resultado['data'];

        $this->jsonResponse([
            'success' => true,
            'detail' => $apiData['detail'] ?? []
        ], 200);
    }
}