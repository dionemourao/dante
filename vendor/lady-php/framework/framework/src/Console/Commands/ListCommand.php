<?php

namespace LadyPHP\Console\Commands;

use LadyPHP\Console\Command;
use LadyPHP\Console\CommandRegistry;
use LadyPHP\Console\Input\InputParser;
use LadyPHP\Console\Output\OutputStyle;

class ListCommand extends Command
{
    protected $name = 'list';
    protected $description = 'Lista todos os comandos disponíveis';
    
    private $registry;
    
    public function __construct(CommandRegistry $registry)
    {
        $this->registry = $registry;
    }

    protected function execute(InputParser $input, OutputStyle $output): int
    {
        $output->title('Comandos Disponíveis');
        
        $commands = $this->registry->all();
        ksort($commands);
        
        foreach ($commands as $name => $command) {
            $output->writeln("<green>{$name}</green>: {$command->getDescription()}");
        }
        
        $output->writeln("\nUse <yellow>lady help [comando]</yellow> para mais informações sobre um comando específico.");
        
        return 0;
    }
}