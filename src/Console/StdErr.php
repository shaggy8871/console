<?php

namespace Console;

/**
 * Switches between ansi and non-ansi output
 */

class StdErr extends StdOut implements OutStream {

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
