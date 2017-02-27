<?php

namespace Console;

class Runner
{

    protected $appName;
    protected $appVersion;
    protected $self;
    protected $commands = [];

    public function __construct($appName = 'Console Application', $appVersion = '1.0')
    {

        $this->appName = $appName;
        $this->appVersion = $appVersion;
        $this->self = (isset($GLOBALS['argv'][0]) ? $GLOBALS['argv'][0] : '<filename>');

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

    public function run()
    {

        $args = new Args();
        $commands = $args->getCommands();

        if (empty($commands)) {
            $this->listHelp();
            return;
        }

        foreach($commands as $command) {
            if (isset($this->commands[$command])) {
                $commandInstance = (new $this->commands[$command])->execute($args->getArgs());
            } else {
                throw new Exception\CommandNotFoundException($command);
            }
        }

    }

    public function listHelp()
    {

        echo sprintf("%s %s\n\n", $this->appName, $this->appVersion);
        echo sprintf("usage: %s [command] [arguments]\n\n", $this->self);

        echo "Commands:\n";
        echo "  " . implode("\n  ", $this->listCommands());
        echo "\n";

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