<?php

namespace Console;

abstract class AbstractFormattableStream {

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
        static::$ansiEnabled[ static::class ] = true;
    }

    /**
     * Disable ansi mode
     * 
     * @return void
     */
    public static function disableAnsi(): void
    {
        static::$ansiEnabled[ static::class ] = false;
    }

    /**
     * Returns the ansi flag state
     * 
     * @return bool
     */
    public static function isAnsiEnabled(): bool
    {
        return static::$ansiEnabled[ static::class ] ?? true;
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