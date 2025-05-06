<?php

namespace LadyPHP\Support;

abstract class ServiceProvider
{
    /**
     * A instância da aplicação
     */
    protected $app;

    /**
     * Cria uma nova instância do provedor de serviços
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Registra os serviços no contêiner
     */
    abstract public function register();

    /**
     * Inicializa os serviços
     */
    public function boot()
    {
        //
    }
}