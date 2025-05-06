<?php

// Definir constantes
define('BASE_PATH', dirname(__DIR__));
define('VIEWS_PATH', BASE_PATH . '/resources/views');
define('CACHE_PATH', BASE_PATH . '/storage/cache/views');

// Carregar o autoloader
require_once BASE_PATH . '/vendor/autoload.php';

use Lady\Router\Router;
use Lady\Router\RouterException;

// Inicializar o router
$router = new Router();

// Incluir arquivo de definição de rotas
require_once BASE_PATH . '/routes/web.php';

// Despachar a requisição
try {
    $router->dispatch();
} catch (RouterException $e) {
    http_response_code($e->getCode());
    
    // Você pode renderizar uma página de erro aqui
    echo '<h1>Erro ' . $e->getCode() . '</h1>';
    echo '<p>' . $e->getMessage() . '</p>';
}