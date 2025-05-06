<?php

require_once 'vendor/autoload.php';

use Lady\Router\Router;
use Lady\Router\RouterException;

// Criar instância do router
$router = new Router();

// Definir rotas
$router->get('/', function() {
    echo "Página inicial";
});

$router->get('/users', 'UserController@index');

$router->get('/users/{id}', function($id) {
    echo "Detalhes do usuário: " . $id;
});

// Rota com middleware
$router->post('/admin/dashboard', 'AdminController@dashboard')
    ->middleware('AuthMiddleware');

// Middleware global
$router->use(function() {
    // Lógica do middleware global
    // Por exemplo, verificar CSRF token
});

// Despachar a requisição
try {
    $router->dispatch();
} catch (RouterException $e) {
    http_response_code($e->getCode());
    echo $e->getMessage();
}