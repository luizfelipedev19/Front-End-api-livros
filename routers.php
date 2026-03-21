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
        "path" => "/logout",
        "controller" => "LogoutController",
        "action" => "logout"
    ],
];