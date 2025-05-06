<?php

namespace LadyPHP\Console\Input;

class InputOption
{
    const VALUE_NONE = 1;
    const VALUE_REQUIRED = 2;
    const VALUE_OPTIONAL = 4;

    private $name;
    private $shortcut;
    private $mode;
    private $description;
    private $default;

    public function __construct(string $name, $shortcut = null, int $mode = self::VALUE_NONE, string $description = '', $default = null)
    {
        $this->name = $name;
        $this->shortcut = $shortcut;
        $this->mode = $mode;
        $this->description = $description;
        $this->default = $default;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getShortcut(): ?string
    {
        return $this->shortcut;
    }

    public function acceptValue(): bool
    {
        return $this->mode !== self::VALUE_NONE;
    }

    public function isValueRequired(): bool
    {
        return $this->mode === self::VALUE_REQUIRED;
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