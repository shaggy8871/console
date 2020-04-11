<?php

namespace Console;

/**
 * Switches between ansi and non-ansi output
 */

class StdOut
{

    /**
     * @var bool
     */
    protected static $ansiEnabled = true;

    /**
     * Enable ansi mode
     * 
     * @return void
     */
    public static function enableAnsi(): void
    {
        static::$ansiEnabled = true;
    }

    /**
     * Disable ansi mode
     * 
     * @return void
     */
    public static function disableAnsi(): void
    {
        static::$ansiEnabled = false;
    }

    /**
     * Returns the ansi flag state
     * 
     * @return bool
     */
    public static function isAnsiEnabled(): bool
    {
        return static::$ansiEnabled;
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
            if (static::$ansiEnabled) {
                $output .= Decorate::color($text, $colors);
            } else {
                $output .= $text;
            }
        }

        return $output;
    }

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