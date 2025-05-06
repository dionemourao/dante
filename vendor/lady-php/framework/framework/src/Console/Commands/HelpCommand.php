<?php

namespace LadyPHP\Console\Commands;

use LadyPHP\Console\Command;
use LadyPHP\Console\CommandRegistry;
use LadyPHP\Console\Input\InputArgument;
use LadyPHP\Console\Input\InputParser;
use LadyPHP\Console\Output\OutputStyle;

class HelpCommand extends Command
{
    protected $name = 'help';
    protected $description = 'Exibe ajuda para um comando';
    
    private $registry;
    
    public function __construct(CommandRegistry $registry)
    {
        $this->registry = $registry;
    }
    
    protected function configure(): void
    {
        $this->addArgument('command', InputArgument::OPTIONAL, 'O comando para exibir ajuda');
    }

    protected function execute(InputParser $input, OutputStyle $output): int
    {
        $commandName = $input->getArgument('command');
        
        if (!$commandName) {
            $output->writeln("Use <yellow>lady help [comando]</yellow> para exibir ajuda para um comando específico.");
            $output->writeln("Use <yellow>lady list</yellow> para listar todos os comandos disponíveis.");
            return 0;
        }
        
        $command = $this->registry->get($commandName);
        
        if (!$command) {
            $output->error("Comando '{$commandName}' não encontrado.");
            return 1;
        }
        
        $output->title("Ajuda para o comando '{$commandName}'");
        $output->writeln("<green>Descrição:</green> {$command->getDescription()}");
        
        $arguments = $command->getArguments();
        if (!empty($arguments)) {
            $output->section("Argumentos:");
            foreach ($arguments as $argument) {
                $required = $argument->isRequired() ? '(obrigatório)' : '(opcional)';
                $default = $argument->getDefault() !== null ? " [padrão: '{$argument->getDefault()}']" : '';
                $output->writeln("  <yellow>{$argument->getName()}</yellow> {$required} - {$argument->getDescription()}{$default}");
            }
        }
        
        $options = $command->getOptions();
        if (!empty($options)) {
            $output->section("Opções:");
            foreach ($options as $option) {
                $shortcut = $option->getShortcut() ? "-{$option->getShortcut()}, " : "    ";
                $valueInfo = $option->acceptValue() ? ($option->isValueRequired() ? " <valor>" : " [valor]") : "";
                $default = $option->getDefault() !== null ? " [padrão: '{$option->getDefault()}']" : '';
                $output->writeln("  {$shortcut}--{$option->getName()}{$valueInfo} - {$option->getDescription()}{$default}");
            }
        }
        
        $output->writeln("\n<blue>Uso:</blue>");
        $output->writeln("  lady {$commandName} [opções] [argumentos]");
        
        return 0;
    }
}