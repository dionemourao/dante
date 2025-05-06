<?php

namespace LadyPHP\Console;

class CommandRegistry
{
    /**
     * Lista de comandos registrados
     */
    protected $commands = [];

    /**
     * Registra um comando
     */
    public function register(Command $command): void
    {
        $this->commands[$command->getName()] = $command;
    }

    /**
     * Retorna um comando pelo nome
     */
    public function get(string $name): ?Command
    {
        return $this->commands[$name] ?? null;
    }

    /**
     * Retorna todos os comandos registrados
     */
    public function all(): array
    {
        return $this->commands;
    }

    /**
     * Verifica se um comando existe
     */
    public function has(string $name): bool
    {
        return isset($this->commands[$name]);
    }
}