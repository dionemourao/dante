<?php

namespace LadyPHP\Http;

use LadyPHP\Foundation\Application;

class Kernel
{
    /**
     * A instância da aplicação
     */
    protected $app;

    /**
     * Cria uma nova instância do kernel HTTP
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Processa a requisição HTTP
     */
    public function handle(Request $request): Response
    {
        try {
            // Aqui você pode adicionar middleware, roteamento, etc.
            
            // Por enquanto, apenas retorna uma resposta simples
            return new Response('Lady-PHP Framework', 200, [
                'Content-Type' => 'text/html; charset=UTF-8'
            ]);
        } catch (\Exception $e) {
            return new Response('Erro: ' . $e->getMessage(), 500);
        }
    }
}