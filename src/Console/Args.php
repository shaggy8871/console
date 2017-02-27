<?php

namespace Console;

class Args
{

    protected $args = [];
    protected $commands = [];

    public function __construct(array $argv)
    {

        if (count($argv) > 1) {
            $this->parseArgs($argv);
        }

    }

    public function __get($name)
    {

        return (isset($this->args[$name]) ? $this->args[$name] : null);

    }

    public function __isset($name)
    {

        return isset($this->args[$name]);

    }

    public function getAll()
    {

        return $this->args;

    }

    public function getCommands()
    {

        return $this->commands;

    }

    public function setAliases(array $aliases)
    {

        foreach($aliases as $k => $v) {
            if (isset($this->args[$k])) {
                $this->args[$v] = $this->args[$k];
                unset($this->args[$k]);
            }
        }

    }

    private function parseArgs(array $args)
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