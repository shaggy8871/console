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

        $this->self = (isset($this->argv[0]) ? $this->argv[0] : '<filename>');

    }

    public function register($name, $command)
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

    public function setAppName($appName, $appVersion = null)
    {

        $this->appName = $appName;
        if ($appVersion) {
            $this->appVersion = $appVersion;
        }

    }

    public function setAppVersion($appVersion)
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
            if (!isset($this->commands[$command])) {
                throw new Exception\CommandNotFoundException("Command not found: '" . $command . "'");
            }
            if ($this->commands[$command] instanceof CommandInterface) {
                $this->commands[$command]->execute($args);
            } else {
                $commandParsed = $this->parseCommand($command, $this->commands[$command]);
                if (!class_exists($commandParsed->class)) {
                    throw new Exception\CommandClassNotValid("Class not found for command: '" . $command . "'");
                }
                $commandInstance = new $commandParsed->class;
                if (!($commandInstance instanceof CommandInterface)) {
                    throw new Exception\CommandClassNotValid("Class '" . $commandParsed->class . "' does not extend CommandInterface");
                }
                $commandInstance->execute($args);
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
        $outp .= "\n\n";

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
            try {
                $command = $this->parseCommand($name, $command);
                $commandsFormatted[] = $name . ($command->description ? ' - ' . $command->description : '');
            } catch (Exception\CommandClassNotValid $e) {
                $commandsFormatted[] = $name . ' (error parsing command)';
            }
        }

        return $commandsFormatted;

    }

    private function parseCommand($name, $command)
    {

        if ($command instanceof CommandInterface) {
            return (object) [
                'class' => get_class($command),
                'description' => method_exists($command, 'getDescription') ? $command->getDescription() : '',
            ];
        } else
        if (is_array($command)) {
            if (!isset($command['class'])) {
                throw new Exception\CommandClassNotValid("Array does not contain 'class' value for command '" . $name . "'");
            }
            return (object) [
                'class' => $command['class'],
                'description' => isset($command['description']) ? $command['description'] : ''
            ];
        } else {
            return (object) [
                'class' => $command,
                'description' => ''
            ];
        }

    }

}