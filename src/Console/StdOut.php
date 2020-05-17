<?php

namespace Console;

class StdOut extends AbstractFormattableStream implements OutStreamInterface
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