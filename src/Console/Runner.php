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

    /**
     * Register a command
     * 
     * @param string the command name
     * @param mixed the command in array or class form
     * 
     * @return Console\Runner
     */
    public function register(string $name, $command): self
    {
        if (!isset($this->commands[$name])) {
            $this->commands[$name] = $command;
        } else {
            throw new Exception\CommandAlreadyRegisteredException();
        }

        return $this;
    }

    /**
     * Register all commands in one go
     * 
     * @param array the commands with name/command pairs
     * 
     * @return Console\Runner
     */
    public function registerAll(array $commands): self
    {
        foreach($commands as $name => $command) {
            $this->register($name, $command);
        }

        return $this;
    }

    /**
     * De-register a command
     * 
     * @param string the command name
     * 
     * @return Console\Runner
     */
    public function deregister(string $name): self
    {
        if (isset($this->commands[$name])) {
            unset($this->commands[$name]);
        } else {
            throw new Exception\CommandNotFoundException();
        }

        return $this;
    }

    /**
     * De-register all commands in the specified array
     * 
     * @param array the commands to de-register
     * 
     * @return Console\Runner
     */
    public function deregisterAll(array $commands): self
    {
        foreach($commands as $name) {
            $this->deregister($name);
        }

        return $this;
    }

    /**
     * Set the app name
     * 
     * @param string the app name
     * @param string the app version
     * 
     * @return void
     */
    public function setAppName(string $appName, ?string $appVersion = null): void
    {
        $this->appName = $appName;
        if ($appVersion) {
            $this->appVersion = $appVersion;
        }
    }

    /**
     * Set the app version
     * 
     * @param string the app version
     * 
     * @return void
     */
    public function setAppVersion(string $appVersion): void
    {
        $this->appVersion = $appVersion;
    }

    /**
     * Set the arguments manually
     * 
     * @param array the list of arguments
     * 
     * @return void
     */
    public function setArguments(array $argv): void
    {
        $this->argv = $argv;
    }

    /**
     * Run the console app
     * 
     * @return void
     */
    public function run(): void
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

    /**
     * Get the help text
     * 
     * @return string
     */
    public function getHelp(): string
    {
        $outp  = '';
        $outp .= sprintf("%s %s\n\n", $this->appName, $this->appVersion);
        $outp .= sprintf("usage: %s [command] [arguments]\n\n", $this->self);

        $outp .= "Commands:\n";
        $outp .= "  " . implode("\n  ", $this->listCommands());
        $outp .= "\n\n";

        return $outp;
    }

    /**
     * Show the help text
     * 
     * @return void
     */
    public function showHelp(): void
    {
        echo $this->getHelp();
    }

    /**
     * List the commands available
     * 
     * @return array
     */
    public function listCommands(): array
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

    /**
     * Parse a single command into an array
     * 
     * @param string the command name
     * @param mixed the command
     * 
     * @return stdClass
     */
    private function parseCommand(string $name, $command): \stdClass
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