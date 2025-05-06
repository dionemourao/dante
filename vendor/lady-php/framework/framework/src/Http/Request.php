<?php

namespace LadyPHP\Http;

class Request
{
    /**
     * Parâmetros da requisição
     */
    protected $parameters = [];

    /**
     * Cria uma nova instância de Request
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Cria uma instância de Request a partir da requisição atual
     */
    public static function capture()
    {
        return new static($_REQUEST);
    }

    /**
     * Obtém um parâmetro da requisição
     */
    public function get(string $key, $default = null)
    {
        return $this->parameters[$key] ?? $default;
    }

    /**
     * Verifica se um parâmetro existe na requisição
     */
    public function has(string $key): bool
    {
        return isset($this->parameters[$key]);
    }

    /**
     * Obtém todos os parâmetros da requisição
     */
    public function all(): array
    {
        return $this->parameters;
    }
}