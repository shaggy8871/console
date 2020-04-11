<?php

namespace Console;

class Args
{

    /**
     * @var array
     */
    protected $args = [];

    /**
     * @var array
     */
    protected $commands = [];

    public function __construct(array $argv)
    {
        if (count($argv) > 1) {
            $this->parseArgs($argv);
        }
    }

    /**
     * Returns an argument by name
     * 
     * @param string
     * 
     * @return string|null
     */
    public function __get(string $name): ?string
    {
        return (array_key_exists($name, $this->args) ? $this->args[$name] : null);
    }

    /**
     * Returns true if the argument is set
     * 
     * @param string
     * 
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->args);
    }

    /**
     * Returns a list of arguments supplied in key/value pairs
     * 
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * Returns a list of commands supplied
     * 
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * Alias function to getArgs()
     */
    public function getAll(): array
    {
        return $this->getArgs();
    }

    /**
     * Set aliases (and remove the original)
     * 
     * @param array a list of aliases in key/value form
     * 
     * @return void
     */
    public function setAliases(array $aliases): void
    {
        foreach($aliases as $k => $v) {
            if (array_key_exists($k, $this->args)) {
                $this->args[$v] = $this->args[$k];
                unset($this->args[$k]);
            }
        }
    }

    /**
     * Parse the arguments list into parameters and commands
     * 
     * @param array the console arguments
     * 
     * @return void
     */
    private function parseArgs(array $args): void
    {
        array_shift($args); // remove filename
        $skipNext = false;

        foreach($args as $i => $arg) {
            if (substr($arg, 0, 2) == '--') {
                // Long form, eg --some-arg=""
                $argSplit = explode('=', $arg, 2);
                $argTitle = preg_replace('/^--/', '', $argSplit[0]);
                $argValue = (isset($argSplit[1]) ? str_replace('"', '', trim($argSplit[1])) : null);
                $this->args[$argTitle] = $argValue;
                $skipNext = false;
            } else
            if (substr($arg, 0, 1) == '-') {
                // Short form, eg -v 123
                $argTitle = preg_replace('/^-/', '', $arg);
                $argValue = (isset($args[$i + 1]) && (substr($args[$i + 1], 0, 1) != '-') ? trim($args[$i + 1]) : null);
                $this->args[$argTitle] = $argValue;
                $skipNext = true;
            } else {
                if ($skipNext) {
                    $skipNext = false;
                    continue;
                }
                // Command form, eg app:create
                $this->commands[] = $arg;
            }
        }
    }

}