<?php

namespace LadyPHP\Console;

use LadyPHP\Console\Input\InputArgument;
use LadyPHP\Console\Input\InputOption;
use LadyPHP\Console\Input\InputParser;
use LadyPHP\Console\Output\OutputStyle;

abstract class Command
{
    /**
     * Nome do comando
     */
    protected $name;

    /**
     * Descrição do comando
     */
    protected $description;

    /**
     * Argumentos do comando
     */
    protected $arguments = [];

    /**
     * Opções do comando
     */
    protected $options = [];

    /**
     * Configura o comando, definindo argumentos e opções
     */
    protected function configure(): void
    {
        // Método a ser sobrescrito pelos comandos concretos
    }

    /**
     * Adiciona um argumento ao comando
     */
    protected function addArgument(string $name, int $mode = InputArgument::OPTIONAL, string $description = '', $default = null): self
    {
        $this->arguments[] = new InputArgument($name, $mode, $description, $default);
        return $this;
    }

    /**
     * Adiciona uma opção ao comando
     */
    protected function addOption(string $name, $shortcut = null, int $mode = InputOption::VALUE_NONE, string $description = '', $default = null): self
    {
        $this->options[] = new InputOption($name, $shortcut, $mode, $description, $default);
        return $this;
    }

    /**
     * Retorna o nome do comando
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Retorna a descrição do comando
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Retorna os argumentos do comando
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Retorna as opções do comando
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Executa o comando
     */
    public function run(array $argv, OutputStyle $output): int
    {
        $this->configure();
        
        $parser = new InputParser($this->arguments, $this->options);
        $parser->parse($argv);
        
        return $this->execute($parser, $output);
    }

    /**
     * Executa a lógica do comando
     */
    abstract protected function execute(InputParser $input, OutputStyle $output): int;
}