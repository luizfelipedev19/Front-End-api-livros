<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Utils/flash.php';
require_once __DIR__ . '/../Utils/auth.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class UploadFotoController {
    public function uploadFotoPerfil(): void { 
        verifyAuth();

        $foto = $_FILES['foto'] ?? null;

        if(!$foto || $foto['error'] !== UPLOAD_ERR_OK) {
            setFlash('Nenhuma foto enviada', 'erro');
            header('Location: /Front-Biblioteca/home');
            exit();
        }

        $extensaoPermitida = ['jpg', 'jpeg', 'png'];
        $extensao = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));

        if(!in_array($extensao, $extensaoPermitida)){
            setFlash('Formato de foto inválido. Use jpg, jpeg ou png', 'erro');
            header('Location: /Front-Biblioteca/home');
            exit();
        }

        if($foto['size'] > 5 * 1024 * 1024) {
            setFlash('A foto deve ser menor que 5MB', 'erro');
            header('Location: /Front-Biblioteca/home');
            exit();
        }

        $config = require __DIR__ . '/../Utils/cloudinary.php';

        Configuration::instance([
            'cloud' => [
                'cloud_name' => $config['cloud_name'],
                'api_key'    => $config['api_key'],
                'api_secret' => $config['api_secret']
            ]
        ]);

        try {
            $upload = new UploadApi();

            $resultado = $upload->upload($foto['tmp_name'], [
                'folder'   => 'biblioteca/fotos',
                'public_id' => $_SESSION['UUID'],
                'overwrite' => true,
                'transformation' => [
                    'width'  => 200,
                    'height' => 200,
                    'crop'   => 'fill'
                ]
            ]);

            $urlFoto = $resultado['secure_url'] ?? null;

        } catch (Exception $e) {
            setFlash('Erro ao enviar foto: ' . $e->getMessage(), 'erro');
            header('Location: /Front-Biblioteca/home');
            exit();
        }

        $token = $_SESSION['token'] ?? null;
        $uuid  = $_SESSION['UUID'] ?? null;

        $ch = curl_init('http://api_livros_app:80/usuario/foto');

        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => 'PATCH',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $token,
                'X-User-UUID: ' . $uuid
            ],
            CURLOPT_POSTFIELDS => json_encode(['url_foto' => $urlFoto])
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        file_put_contents(__DIR__ . '/../../debug.log', "HTTP: $httpCode, Response: $response\n, $urlFoto" . PHP_EOL, FILE_APPEND);

        if($httpCode === 200) {
            $_SESSION['foto_perfil'] = $urlFoto;
            setFlash('Foto atualizada com sucesso!', 'success');
        } else {
            setFlash('Erro ao atualizar foto no perfil', 'erro');
        }

        header('Location: /Front-Biblioteca/home');
        exit();
    }
}