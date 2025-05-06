<?php

namespace Lady\Console\Command;

use Lady\Console\CommandInterface;

abstract class Command implements CommandInterface
{
    /**
     * Nome do comando
     *
     * @var string
     */
    protected string $name = '';
    
    /**
     * Descrição do comando
     *
     * @var string
     */
    protected string $description = '';
    
    /**
     * Sintaxe do comando
     *
     * @var string
     */
    protected string $syntax = '';
    
    /**
     * Obtém o nome do comando
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Obtém a descrição do comando
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    /**
     * Obtém a sintaxe do comando
     *
     * @return string
     */
    public function getSyntax(): string
    {
        return $this->syntax;
    }
    
    /**
     * Escreve uma mensagem no console
     *
     * @param string $message
     * @param string $type (info, success, error, warning)
     * @return void
     */
    protected function write(string $message, string $type = 'info'): void
    {
        $colors = [
            'info' => "\033[0;36m", // Ciano
            'success' => "\033[0;32m", // Verde
            'error' => "\033[0;31m", // Vermelho
            'warning' => "\033[0;33m", // Amarelo
            'reset' => "\033[0m" // Reset
        ];
        
        echo $colors[$type] . $message . $colors['reset'] . PHP_EOL;
    }
    
    /**
     * Escreve uma mensagem de informação
     *
     * @param string $message
     * @return void
     */
    protected function info(string $message): void
    {
        $this->write($message, 'info');
    }
    
    /**
     * Escreve uma mensagem de sucesso
     *
     * @param string $message
     * @return void
     */
    protected function success(string $message): void
    {
        $this->write($message, 'success');
    }
    
    /**
     * Escreve uma mensagem de erro
     *
     * @param string $message
     * @return void
     */
    protected function error(string $message): void
    {
        $this->write($message, 'error');
    }
    
    /**
     * Escreve uma mensagem de aviso
     *
     * @param string $message
     * @return void
     */
    protected function warning(string $message): void
    {
        $this->write($message, 'warning');
    }
}