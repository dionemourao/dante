<?php

namespace LadyPHP\Http;

class Response
{
    /**
     * Conteúdo da resposta
     */
    protected $content;

    /**
     * Código de status HTTP
     */
    protected $statusCode;

    /**
     * Cabeçalhos HTTP
     */
    protected $headers = [];

    /**
     * Cria uma nova instância de Response
     */
    public function __construct($content = '', int $statusCode = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * Define o conteúdo da resposta
     */
    public function setContent($content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Define o código de status HTTP
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Adiciona um cabeçalho HTTP
     */
    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Envia a resposta
     */
    public function send(): void
    {
        // Define o código de status
        http_response_code($this->statusCode);

        // Define os cabeçalhos
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        // Envia o conteúdo
        echo $this->content;
    }

    /**
     * Cria uma resposta JSON
     */
    public static function json($data, int $statusCode = 200, array $headers = []): self
    {
        $headers['Content-Type'] = 'application/json';
        return new static(json_encode($data), $statusCode, $headers);
    }
}