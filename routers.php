<?php

return [

    [
        "method" => "GET",
        "path" => "/",
        "controller" => "AuthController",
        "action" => "showLogin"
    ],
    [
        "method" => "GET",
        "path" => "/register",
        "controller" => "RegisterController",
        "action" => "showRegister"
    ],
    [
        "method" => "POST",
        "path" => "/login",
        "controller" => "AuthController",
        "action" => "login"
    ],
    [
        "method" => "POST",
        "path" => "/register",
        "controller" => "RegisterController",
        "action" => "registerUser"
    ],
    [
        "method" => "GET",
        "path" => "/home",
        "controller" => "HomeController",
        "action" => "showHome"
    ],
    [
        "method" => "GET",
        "path" => "/home/livros",
        "controller" => "HomeController",
        "action" => "listarLivros"
    ],
    [
        "method" => "GET",
        "path" => "/logout",
        "controller" => "LogoutController",
        "action" => "logout"
    ],
    [
        "method" => "POST",
        "path" => "/upload-foto",
        "controller" => "UploadFotoController",
        "action" => "uploadFotoPerfil"
    ],
    [
        "method" => "POST",
        "path" => "/livros/salvar",
        "controller" => "LivroController",
        "action" => "salvarLivro"
    ],
    [
        "method" => "GET",
        "path" => "/perfil",
        "controller" => "PerfilController",
        "action" => "showPerfil"
    ],
    [
        "method" => "POST",
        "path" => "/livros/editar",
        "controller" => "LivroController",
        "action" => "editarLivro"
    ],
    [
        "method" => "POST",
        "path" => "/livro/deletar",
        "controller" => "LivroController",
        "action" => "deletarLivro"
    ],
    [
        "method" => "POST",
        "path" => "/recuperar-senha",
        "controller" => "SenhaController",
        "action" => "solicitarRecuperacao"
    ],
    [
        "method" => "GET",
        "path" => "/redefinir-senha",
        "controller" => "SenhaController",
        "action" => "mostrarTelaRedefinir"
    ],
    [
        "method" => "POST",
        "path" => "/redefinir-senha",
        "controller" => "SenhaController",
        "action" => "redefinirSenha"
    ]
];