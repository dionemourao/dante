<?php

namespace Lady\Console;

use Exception;

class CommandNotFoundException extends Exception
{
    public function __construct(string $commandName)
    {
        parent::__construct("Command not found: {$commandName}");
    }
}