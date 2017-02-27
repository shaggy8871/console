<?php

namespace Console;

class Runner
{

    protected $appName = 'Console Application';
    protected $appVersion = '1.0';
    protected $self;
    protected $commands = [];
    protected $argv; // original argv array

    public function __construct(array $argv = [])
    {

        if (empty($argv)) {
            $this->argv = $GLOBALS['argv'];
        } else {
            $this->argv = $argv;
        }

        $this->self = (isset($argv[0]) ? $this->argv[0] : '<filename>');

    }

    public function register($name, CommandInterface $command)
    {

        if (!isset($this->commands[$name])) {
            $this->commands[$name] = $command;
        } else {
            throw new Exception\CommandAlreadyRegisteredException();
        }

        return $this;

    }

    public function registerAll(array $commands)
    {

        foreach($commands as $name => $command) {
            $this->register($name, $command);
        }

        return $this;

    }

    public function deregister($name)
    {

        if (isset($this->commands[$name])) {
            unset($this->commands[$name]);
        } else {
            throw new Exception\CommandNotFoundException();
        }

        return $this;

    }

    public function deregisterAll(array $commands)
    {

        foreach($commands as $name) {
            $this->deregister($name);
        }

        return $this;

    }

    public function setAppName($name)
    {

        $this->appName = $appName;

    }

    public function setAppVersion($version)
    {

        $this->appVersion = $appVersion;

    }

    public function setArguments(array $argv)
    {

        $this->argv = $argv;

    }

    public function run()
    {

        $args = new Args($this->argv);

        $commands = $args->getCommands();
        if (empty($commands)) {
            $this->showHelp();
            return;
        }

        foreach($commands as $command) {
            if (isset($this->commands[$command])) {
                $commandInstance = (new $this->commands[$command])->execute($args);
            } else {
                throw new Exception\CommandNotFoundException($command);
            }
        }

    }

    public function getHelp()
    {

        $outp  = '';
        $outp .= sprintf("%s %s\n\n", $this->appName, $this->appVersion);
        $outp .= sprintf("usage: %s [command] [arguments]\n\n", $this->self);

        $outp .= "Commands:\n";
        $outp .= "  " . implode("\n  ", $this->listCommands());
        $outp .= "\n";

        return $outp;

    }

    public function showHelp()
    {

        echo $this->getHelp();

    }

    public function listCommands()
    {

        $commandsFormatted = [];

        foreach($this->commands as $name => $command) {
            $commandsFormatted[] = $name . (method_exists($command, 'getDescription') ? ' - ' . $command->getDescription() : '');
        }

        return $commandsFormatted;

    }

}