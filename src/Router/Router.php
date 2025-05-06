<?php

namespace Lady\Router;

class Router
{
    private RouteCollection $routes;
    private array $globalMiddlewares = [];

    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    public function get(string $path, $handler): Route
    {
        return $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler): Route
    {
        return $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, $handler): Route
    {
        return $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, $handler): Route
    {
        return $this->addRoute('DELETE', $path, $handler);
    }

    public function patch(string $path, $handler): Route
    {
        return $this->addRoute('PATCH', $path, $handler);
    }

    public function any(string $path, $handler): Route
    {
        return $this->addRoute('ANY', $path, $handler);
    }

    public function addRoute(string $method, string $path, $handler): Route
    {
        $route = new Route($method, $path, $handler);
        $this->routes->add($route);
        return $route;
    }

    public function use($middleware): self
    {
        if (is_array($middleware)) {
            $this->globalMiddlewares = array_merge($this->globalMiddlewares, $middleware);
        } else {
            $this->globalMiddlewares[] = $middleware;
        }
        return $this;
    }

    public function dispatch(string $method = null, string $uri = null): mixed
    {
        $method = $method ?? $_SERVER['REQUEST_METHOD'];
        $uri = $uri ?? $this->getRequestUri();

        $route = $this->routes->findRoute($method, $uri);

        if (!$route) {
            throw new RouterException("Route not found: $method $uri", 404);
        }

        // Aplicar middlewares globais e específicos da rota
        $middlewares = array_merge($this->globalMiddlewares, $route->getMiddlewares());
        
        // Executar middlewares
        foreach ($middlewares as $middleware) {
            if (is_callable($middleware)) {
                $middleware();
            } elseif (is_string($middleware) && class_exists($middleware)) {
                $instance = new $middleware();
                if (method_exists($instance, 'handle')) {
                    $instance->handle();
                }
            }
        }

        // Executar o handler da rota
        $handler = $route->getHandler();
        $params = $route->getParams();

        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }

        if (is_string($handler) && strpos($handler, '@') !== false) {
            [$controller, $method] = explode('@', $handler);
            
            if (!class_exists($controller)) {
                throw new RouterException("Controller not found: $controller", 500);
            }
            
            $instance = new $controller();
            
            if (!method_exists($instance, $method)) {
                throw new RouterException("Method not found: $method in $controller", 500);
            }
            
            return call_user_func_array([$instance, $method], $params);
        }

        throw new RouterException("Invalid route handler", 500);
    }

    private function getRequestUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remover query string se existir
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remover barra final se não for a raiz
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = rtrim($uri, '/');
        }
        
        return $uri;
    }
}