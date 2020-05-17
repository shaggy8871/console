<?php

namespace Console;

class StdErr extends StdOut implements OutStreamInterface {

    /**
     * Write content to stderr
     * 
     * @param array the messages to write
     * 
     * @return void
     */
    public static function write(array $messages = []): void
    {
        fwrite(STDERR,static::format($messages));
    }

}
