<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

$routes = require_once __DIR__ . '/routers.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$basePath = '/Front-Biblioteca';

$uri = str_replace($basePath, '', $uri);

if($uri === ''){
    $uri = '/';
}

$routeFound = false;

foreach($routes as $route){
    if($route['method'] === $method && $route['path'] === $uri){
        $routeFound = true;

        $controllerName = $route['controller'];
        $action = $route['action'];

        require_once __DIR__ . '/app/Controller/' . $controllerName . '.php';

        $controller = new $controllerName();
        $controller->$action();

        break;
    }
}

if(!$routeFound){
    http_response_code(404);
    echo "404 - Rota não encontrada";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>