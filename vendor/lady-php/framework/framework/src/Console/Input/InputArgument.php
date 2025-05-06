<?php

namespace LadyPHP\Console\Input;

class InputArgument
{
    const REQUIRED = 1;
    const OPTIONAL = 2;
    const IS_ARRAY = 4;

    private $name;
    private $mode;
    private $description;
    private $default;

    public function __construct(string $name, int $mode = self::OPTIONAL, string $description = '', $default = null)
    {
        $this->name = $name;
        $this->mode = $mode;
        $this->description = $description;
        $this->default = $default;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isRequired(): bool
    {
        return (bool) ($this->mode & self::REQUIRED);
    }

    public function isArray(): bool
    {
        return (bool) ($this->mode & self::IS_ARRAY);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDefault()
    {
        return $this->default;
    }
}