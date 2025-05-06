<?php

namespace LadyPHP\Console\Input;

class InputParser
{
    private $arguments = [];
    private $options = [];
    private $parsedArguments = [];
    private $parsedOptions = [];

    public function __construct(array $arguments = [], array $options = [])
    {
        $this->arguments = $arguments;
        $this->options = $options;
    }

    public function parse(array $argv): void
    {
        // Remove o nome do script
        array_shift($argv);
        
        // Remove o nome do comando
        if (!empty($argv)) {
            array_shift($argv);
        }

        $parsedArguments = [];
        $parsedOptions = [];
        $argIndex = 0;

        foreach ($argv as $token) {
            // Opção longa (--option)
            if (strpos($token, '--') === 0) {
                $name = substr($token, 2);
                $value = true;
                
                if (strpos($name, '=') !== false) {
                    [$name, $value] = explode('=', $name, 2);
                }
                
                $parsedOptions[$name] = $value;
            }
            // Opção curta (-o)
            elseif (strpos($token, '-') === 0) {
                $shortcut = substr($token, 1);
                
                // Encontrar a opção correspondente ao atalho
                foreach ($this->options as $option) {
                    if ($option->getShortcut() === $shortcut) {
                        $parsedOptions[$option->getName()] = true;
                        break;
                    }
                }
            }
            // Argumento
            else {
                if (isset($this->arguments[$argIndex])) {
                    $arg = $this->arguments[$argIndex];
                    
                    if ($arg->isArray()) {
                        if (!isset($parsedArguments[$arg->getName()])) {
                            $parsedArguments[$arg->getName()] = [];
                        }
                        $parsedArguments[$arg->getName()][] = $token;
                    } else {
                        $parsedArguments[$arg->getName()] = $token;
                        $argIndex++;
                    }
                }
            }
        }

        // Definir valores padrão para argumentos não fornecidos
        foreach ($this->arguments as $index => $argument) {
            if (!isset($parsedArguments[$argument->getName()])) {
                if ($argument->isRequired()) {
                    throw new \InvalidArgumentException(sprintf('Argumento "%s" é obrigatório.', $argument->getName()));
                }
                
                $parsedArguments[$argument->getName()] = $argument->getDefault();
            }
        }

        // Definir valores padrão para opções não fornecidas
        foreach ($this->options as $option) {
            if (!isset($parsedOptions[$option->getName()])) {
                $parsedOptions[$option->getName()] = $option->getDefault();
            }
        }

        $this->parsedArguments = $parsedArguments;
        $this->parsedOptions = $parsedOptions;
    }

    public function getArgument(string $name)
    {
        return $this->parsedArguments[$name] ?? null;
    }

    public function getOption(string $name)
    {
        return $this->parsedOptions[$name] ?? null;
    }

    public function hasOption(string $name): bool
    {
        return isset($this->parsedOptions[$name]);
    }

    public function getArguments(): array
    {
        return $this->parsedArguments;
    }

    public function getOptions(): array
    {
        return $this->parsedOptions;
    }
}