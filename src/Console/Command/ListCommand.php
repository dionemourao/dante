<?php

namespace Lady\Console\Command;

use Lady\Console\Lady;

class ListCommand extends Command
{
    /**
     * Nome do comando
     *
     * @var string
     */
    protected string $name = 'list';
    
    /**
     * Descrição do comando
     *
     * @var string
     */
    protected string $description = 'Lista todos os comandos disponíveis';
    
    /**
     * Sintaxe do comando
     *
     * @var string
     */
    protected string $syntax = 'php lady list';
    
    /**
     * Executa o comando
     *
     * @param array $args Argumentos passados para o comando
     * @return int Código de saída (0 para sucesso, outro valor para erro)
     */
    public function execute(array $args): int
    {
        $lady = new Lady();
        $lady->showBanner();
        
        $this->info('Comandos disponíveis:');
        echo PHP_EOL;
        
        $commands = $lady->getCommands();
        ksort($commands);
        
        foreach ($commands as $command) {
            echo "  \033[0;32m" . str_pad($command->getName(), 20) . "\033[0m";
            echo $command->getDescription() . PHP_EOL;
        }
        
        echo PHP_EOL;
        $this->info('Para mais informações sobre um comando específico, execute:');
        echo "  php lady help <comando>" . PHP_EOL;
        
        return 0;
    }
}