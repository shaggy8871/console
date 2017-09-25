<?php
namespace Console\Tests;

use PHPUnit\Framework\TestCase;
use Console\Runner;
use Console\Exception\CommandNotFoundException;

class CommandStringTest extends TestCase
{

    public function testCommandString()
    {

        $this->expectOutputString("AppSimple:" . print_r([], true));

        $console = new Runner();
        // Inject arguments
        $console->setArguments([
            'test.php',
            'app:simple'
        ]);
        // Register handlers
        $console->registerAll([
            'app:simple' => 'Console\Tests\Commands\AppSimple'
        ]);
        // Run test
        $console->run();

    }

    public function testCommandStringRegisterSingle()
    {

        $this->expectOutputString("AppSimple:" . print_r([], true));

        $console = new Runner();
        // Inject arguments
        $console->setArguments([
            'test.php',
            'app:simple'
        ]);
        // Register handlers
        $console->register('app:simple', 'Console\Tests\Commands\AppSimple');
        // Run test
        $console->run();

    }

    public function testCommandStringWithLongArgument()
    {

        $this->expectOutputString("AppSimple:" . print_r([
            'long' => 'argument'
        ], true));

        $console = new Runner();
        // Inject arguments
        $console->setArguments([
            'test.php',
            'app:simple',
            '--long="argument"'
        ]);
        // Register handlers
        $console->registerAll([
            'app:simple' => 'Console\Tests\Commands\AppSimple'
        ]);
        // Run test
        $console->run();

    }

    public function testCommandStringWithLongAndShortArgument()
    {

        $this->expectOutputString("AppSimple:" . print_r([
            'long' => 'argument',
            's' => 'arg'
        ], true));

        $console = new Runner();
        // Inject arguments
        $console->setArguments([
            'test.php',
            'app:simple',
            '--long="argument"',
            '-s',
            'arg'
        ]);
        // Register handlers
        $console->registerAll([
            'app:simple' => 'Console\Tests\Commands\AppSimple'
        ]);
        // Run test
        $console->run();

    }

    public function testCommandStringWithStandaloneShortArgument()
    {

        $this->expectOutputString("AppSimple:" . print_r([
            's' => '',
            'a' => 'b'
        ], true));

        $console = new Runner();
        // Inject arguments
        $console->setArguments([
            'test.php',
            'app:simple',
            '-s',
            '-a',
            'b'
        ]);
        // Register handlers
        $console->registerAll([
            'app:simple' => 'Console\Tests\Commands\AppSimple'
        ]);
        // Run test
        $console->run();

    }

    public function testCommandStringWithAliases()
    {

        // For this test, args get rewritten which impacts the order of the array
        $this->expectOutputString("AppSimple:" . print_r([
            'a' => 'b', // must be first
            'longform' => 'argument' // must be second
        ], true));

        $console = new Runner();
        // Inject arguments
        $console->setArguments([
            'test.php',
            'app:simple',
            '-l',
            'argument',
            '-a',
            'b'
        ]);
        // Register handlers
        $console->registerAll([
            'app:simple' => 'Console\Tests\Commands\AppSimple'
        ]);
        // Run test
        $console->run();

    }

    public function testCommandStringWithInvalidCommand()
    {

        $this->expectException(CommandNotFoundException::class);

        $console = new Runner();
        // Inject arguments
        $console->setArguments([
            'test.php',
            'app:not-found'
        ]);
        // Register handlers
        $console->registerAll([
            'app:simple' => 'Console\Tests\Commands\AppSimple'
        ]);
        // Run test
        $console->run();

    }

    public function testCommandStringWithNoCommand()
    {

        $console = new Runner();
        // Register handlers
        $console->registerAll([
            'app:simple' => 'Console\Tests\Commands\AppSimple'
        ]);
        // Inject arguments
        $console->setArguments([
            'test.php'
        ]);

        $this->expectOutputString($console->getHelp());

        // Run test
        $console->run();

    }

    public function testCommandStringWithDescription()
    {

        $console = new Runner();
        // Register handlers
        $console->registerAll([
            'app:simple' => [
                'class' => 'Console\Tests\Commands\AppSimple',
                'description' => 'Some description'
            ]
        ]);

        $this->expectOutputRegex('/app:simple - Some description/');

        // Run test
        $console->run();

    }

    public function testCommandStringWithDescriptionRegisterSingle()
    {

        $console = new Runner();
        // Register handlers
        $console->register('app:simple', [
            'class' => 'Console\Tests\Commands\AppSimple',
            'description' => 'Some description'
        ]);

        $this->expectOutputRegex('/app:simple - Some description/');

        // Run test
        $console->run();

    }

}