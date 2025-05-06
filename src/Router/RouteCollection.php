<?php

namespace Lady\Router;

class RouteCollection
{
    private array $routes = [];

    public function add(Route $route): self
    {
        $this->routes[] = $route;
        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function findRoute(string $method, string $path): ?Route
    {
        $method = strtoupper($method);
        
        foreach ($this->routes as $route) {
            if ($route->getMethod() !== $method) {
                continue;
            }

            $pattern = $this->convertPathToRegex($route->getPath());
            if (preg_match($pattern, $path, $matches)) {
                $params = array_filter($matches, function ($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);
                
                return $route->setParams($params);
            }
        }

        return null;
    }

    private function convertPathToRegex(string $path): string
    {
        // Converte parâmetros de rota como /users/{id} para expressão regular
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}