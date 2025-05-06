<?php

namespace LadyPHP;

class Application
{
    private static $instance;
    
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    public function run()
    {
        // Inicializar componentes do framework
        // Processar a requisição
        // Rotear para o controlador apropriado
        // Renderizar a resposta
    }
}