<?php

namespace LadyPHP\Console\Output;

class OutputStyle
{
    private $formatter;

    public function __construct()
    {
        $this->formatter = new OutputFormatter();
    }

    public function setDecorated(bool $decorated): void
    {
        $this->formatter->setDecorated($decorated);
    }

    public function write(string $message, bool $newline = false): void
    {
        $message = $this->formatter->format($message);
        
        if ($newline) {
            $message .= PHP_EOL;
        }
        
        echo $message;
    }

    public function writeln(string $message