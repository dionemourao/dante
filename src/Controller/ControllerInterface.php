<?php

namespace Lady\Controller;

interface ControllerInterface
{
    /**
     * Renderiza uma view
     *
     * @param string $view Nome da view a ser renderizada
     * @param array $data Dados a serem passados para a view
     * @return mixed
     */
    public function view(string $view, array $data = []);
    
    /**
     * Retorna dados em formato JSON
     *
     * @param mixed $data Dados a serem convertidos para JSON
     * @param int $statusCode Código de status HTTP
     * @return mixed
     */
    public function json($data, int $statusCode = 200);
    
    /**
     * Redireciona para outra URL
     *
     * @param string $url URL para redirecionamento
     * @param int $statusCode Código de status HTTP
     * @return mixed
     */
    public function redirect(string $url, int $statusCode = 302);
}