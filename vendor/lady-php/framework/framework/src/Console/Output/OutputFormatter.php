<?php

namespace LadyPHP\Console\Output;

class OutputFormatter
{
    private static $foregroundColors = [
        'black' => '0;30',
        'red' => '0;31',
        'green' => '0;32',
        'yellow' => '0;33',
        'blue' => '0;34',
        'magenta' => '0;35',
        'cyan' => '0;36',
        'white' => '0;37',
        'default' => '0;39',
    ];

    private static $backgroundColors = [
        'black' => '40',
        'red' => '41',
        'green' => '42',
        'yellow' => '43',
        'blue' => '44',
        'magenta' => '45',
        'cyan' => '46',
        'white' => '47',
        'default' => '49',
    ];

    private static $options = [
        'bold' => '1',
        'underscore' => '4',
        'blink' => '5',
        'reverse' => '7',
        'conceal' => '8',
    ];

    private $decorated = true;

    public function setDecorated(bool $decorated): void
    {
        $this->decorated = $decorated;
    }

    public function isDecorated(): bool
    {
        return $this->decorated;
    }

    public function format(string $message): string
    {
        if (!$this->isDecorated()) {
            return preg_replace('/\<([a-z=;]+)\>|\<\/([a-z=;]+)\>/i', '', $message);
        }

        $message = preg_replace_callback('/\<([a-z=;]+)\>(.*?)\<\/\\1\>/is', function ($matches) {
            $style = $matches[1];
            $text = $matches[2];
            
            return $this->applyStyle($style, $text);
        }, $message);

        return $message;
    }

    private function applyStyle(string $style, string $text): string
    {
        $styles = explode(';', $style);
        $codes = [];

        foreach ($styles as $style) {
            if (isset(self::$foregroundColors[$style])) {
                $codes[] = self::$foregroundColors[$style];
            } elseif (isset(self::$backgroundColors[$style])) {
                $codes[] = self::$backgroundColors[$style];
            } elseif (isset(self::$options[$style])) {
                $codes[] = self::$options[$style];
            }
        }

        if (empty($codes)) {
            return $text;
        }

        return sprintf("\033[%sm%s\033[0m", implode(';', $codes), $text);
    }
}