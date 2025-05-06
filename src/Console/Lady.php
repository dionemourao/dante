<?php

namespace Lady\Console;

use Lady\Console\Command\ListCommand;
use Lady\Console\Command\HelpCommand;
use Lady\Console\Command\MakeControllerCommand;
use Lady\Console\Command\MakeModelCommand;
use Lady\Console\Command\MakeViewCommand;
use Lady\Console\Command\MakeMiddlewareCommand;
use Lady\Console\Command\ServeCommand;
use Lady\Console\Command\MigrateCommand;
use Lady\Console\Command\KeyGenerateCommand;

class Lady
{
    /**
     * Versão do Lady CLI
     *
     * @var string
     */
    const VERSION = '1.0.0';
    
    /**
     * Lista de comandos disponíveis
     *
     * @var array
     */
    protected array $commands = [];
    
    /**
     * Construtor
     */
    public function __construct()
    {
        $this->registerCommands();
    }
    
    /**
     * Registra os comandos disponíveis
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        $this->addCommand(new ListCommand());
        $this->addCommand(new HelpCommand());
        $this->addCommand(new MakeControllerCommand());
        $this->addCommand(new MakeModelCommand());
        $this->addCommand(new MakeViewCommand());
        $this->addCommand(new MakeMiddlewareCommand());
        $this->addCommand(new ServeCommand());
        $this->addCommand(new MigrateCommand());
        $this->addCommand(new KeyGenerateCommand());
    }
    
    /**
     * Adiciona um comando à lista
     *
     * @param CommandInterface $command
     * @return self
     */
    public function addCommand(CommandInterface $command): self
    {
        $this->commands[$command->getName()] = $command;
        return $this;
    }
    
    /**
     * Obtém um comando pelo nome
     *
     * @param string $name
     * @return CommandInterface
     * @throws CommandNotFoundException
     */
    public function getCommand(string $name): CommandInterface
    {
        if (!isset($this->commands[$name])) {
            throw new CommandNotFoundException($name);
        }
        
        return $this->commands[$name];
    }
    
    /**
     * Obtém todos os comandos registrados
     *
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }
    
    /**
     * Executa o Lady CLI
     *
     * @param array $args Argumentos da linha de comando
     * @return int Código de saída
     */
    public function run(array $args = []): int
    {
        // Remover o nome do script (primeiro argumento)
        array_shift($args);
        
        // Se não houver argumentos, mostrar a lista de comandos
        if (empty($args)) {
            return $this->getCommand('list')->execute([]);
        }
        
        // Obter o nome do comando
        $commandName = array_shift($args);
        
        try {
            // Obter e executar o comando
            $command = $this->getCommand($commandName);
            return $command->execute($args);
        } catch (CommandNotFoundException $e) {
            echo "\033[0;31mError: " . $e->getMessage() . "\033[0m" . PHP_EOL;
            echo "Run 'php lady list' to see available commands." . PHP_EOL;
            return 1;
        } catch (\Exception $e) {
            echo "\033[0;31mError: " . $e->getMessage() . "\033[0m" . PHP_EOL;
            return 1;
        }
    }
    
    /**
     * Exibe o banner do Lady CLI
     *
     * @return void
     */
    public function showBanner(): void
    {
        echo "\033[0;36m";
        echo "  _               _       " . PHP_EOL;
        echo " | |    __ _  __| |_   _ " . PHP_EOL;
        echo " | |   / _` |/ _` | | | |" . PHP_EOL;
        echo " | |__| (_| | (_| | |_| |" . PHP_EOL;
        echo " |_____\__,_|\__,_|\__, |" . PHP_EOL;
        echo "                   |___/ " . PHP_EOL;
        echo "\033[0m";
        echo "Lady CLI v" . self::VERSION . PHP_EOL;
        echo PHP_EOL;
    }
}