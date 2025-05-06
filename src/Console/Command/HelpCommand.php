<?php

namespace Lady\Console\Command;

use Lady\Console\Lady;
use Lady\Console\CommandNotFoundException;

class HelpCommand extends Command
{
    /**
     * Nome do comando
     *
     * @var string
     */
    protected string $name = 'help';
    
    /**
     * Descrição do comando
     *
     * @var string
     */
    protected string $description = 'Exibe ajuda para um comando específico';
    
    /**
     * Sintaxe do comando
     *
     * @var string
     */
    protected string $syntax = 'php lady help [comando]';
    
    /**
     * Executa o comando
     *
     * @param array $args Argumentos passados para o comando
     * @return int Código de saída (0 para sucesso, outro valor para erro)
     */
    public function execute(array $args): int
    {
        $lady = new Lady();
        
        if (empty($args)) {
            // Se nenhum comando for especificado, mostrar a lista de comandos
            return $lady->getCommand('list')->execute([]);
        }
        
        $commandName = $args[0];
        
        try {
            $command = $lady->getCommand($commandName);
            
            $this->info("Comando: {$command->getName()}");
            echo PHP_EOL;
            
            $this->info("Descrição:");
            echo "  " . $command->getDescription() . PHP_EOL;
            echo PHP_EOL;
            
            $this->info("Uso:");
            echo "  " . $command->getSyntax() . PHP_EOL;
            
            return 0;
        } catch (CommandNotFoundException $e) {
            $this->error("Comando '{$commandName}' não encontrado.");
            echo "Execute 'php lady list' para ver a lista de comandos disponíveis." . PHP_EOL;
            return 1;
        }
    }
}