<?php

namespace Console;

/**
 * Switches between ansi and non-ansi output
 */

interface OutStream {
    static function write(array $messages = []);
}

abstract class FormattableStream {

    /**
     * @var array
     */
    protected static $ansiEnabled = [ ];

    /**
     * Enable ansi mode
     * 
     * @return void
     */
    public static function enableAnsi(): void
    {
        static::$ansiEnabled[ get_called_class() ] = true;
    }

    /**
     * Disable ansi mode
     * 
     * @return void
     */
    public static function disableAnsi(): void
    {
        static::$ansiEnabled[ get_called_class() ] = false;
    }

    /**
     * Returns the ansi flag state
     * 
     * @return bool
     */
    public static function isAnsiEnabled(): bool
    {
        return static::$ansiEnabled[ get_called_class() ] ?? true;
    }

    /**
     * Format content
     * 
     * @param array the messages to format
     * 
     * @return string
     */
    public static function format(array $messages = []): string
    {
        $output = '';

        foreach($messages as $message) {
            list($text, $colors) = $message;
            if (static::isAnsiEnabled()) {
                $output .= Decorate::color($text, $colors);
            } else {
                $output .= $text;
            }
        }

        return $output;
    }

}

class StdOut extends FormattableStream implements OutStream
{

    /**
     * Write content to stdout
     * 
     * @param array the messages to write
     * 
     * @return void
     */
    public static function write(array $messages = []): void
    {
        echo static::format($messages);
    }

}