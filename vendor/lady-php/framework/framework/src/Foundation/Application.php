<?php

namespace LadyPHP\Foundation;

use LadyPHP\Console\CommandRegistry;
use LadyPHP\Console\Commands\ServeCommand;
use LadyPHP\Console\Commands\ListCommand;
use LadyPHP\Console\Commands\HelpCommand;
use LadyPHP\Console\Output\OutputStyle;

class Application
{
    /**
     * Versão do framework
     */
    const VERSION = '1.0.0';

    /**
     * Caminho base da aplicação
     */
    protected $basePath;

    /**
     * Registro de comandos
     */
    protected $commandRegistry;

    /**
     * Serviços registrados
     */
    protected $services = [];

    /**
     * Provedores de serviços
     */
    protected $serviceProviders = [];

    /**
     * Construtor
     */
    public function __construct(string $basePath = null)
    {
        $this->basePath = $basePath ?: getcwd();
        $this->commandRegistry = new CommandRegistry();
        
        $this->bootstrapApplication();
    }

    /**
     * Inicializa a aplicação
     */
    protected function bootstrapApplication(): void
    {
        // Aqui você pode carregar configurações, registrar serviços, etc.
    }

    /**
     * Obtém o caminho base da aplicação
     */
    public function basePath(string $path = ''): string
    {
        return $this->basePath . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }

    /**
     * Registra os comandos padrão
     */
    public function registerDefaultCommands(): void
    {
        // Registra os comandos padrão
        $this->commandRegistry->register(new ServeCommand());
        $this->commandRegistry->register(new ListCommand($this->commandRegistry));
        $this->commandRegistry->register(new HelpCommand($this->commandRegistry));
    }

    /**
     * Registra um comando personalizado
     */
    public function registerCommand($command): void
    {
        $this->commandRegistry->register($command);
    }

    /**
     * Executa a aplicação no modo console
     */
    public function run(array $argv): int
    {
        $output = new OutputStyle();

        // Se não houver argumentos, mostra a lista de comandos
        if (count($argv) <= 1) {
            $output->title('Lady-PHP Framework v' . self::VERSION);
            $listCommand = $this->commandRegistry->get('list');
            return $listCommand ? $listCommand->run(['list'], $output) : 1;
        }

        $commandName = $argv[1];
        $command = $this->commandRegistry->get($commandName);

        if (!$command) {
            $output->error("Comando '{$commandName}' não encontrado.");
            return 1;
        }

        return $command->run($argv, $output);
    }

    /**
     * Registra um serviço no contêiner
     */
    public function bind(string $name, $concrete): void
    {
        $this->services[$name] = $concrete;
    }

    /**
     * Obtém um serviço do contêiner
     */
    public function make(string $name)
    {
        if (!isset($this->services[$name])) {
            throw new \InvalidArgumentException("Serviço '{$name}' não encontrado.");
        }

        $concrete = $this->services[$name];

        if (is_callable($concrete)) {
            return $concrete($this);
        }

        return $concrete;
    }

    /**
     * Registra um provedor de serviços
     */
    public function register($provider): void
    {
        if (is_string($provider)) {
            $provider = new $provider($this);
        }

        $provider->register();

        $this->serviceProviders[] = $provider;
    }

    /**
     * Inicializa todos os provedores de serviços
     */
    public function boot(): void
    {
        foreach ($this->serviceProviders as $provider) {
            if (method_exists($provider, 'boot')) {
                $provider->boot();
            }
        }
    }
}