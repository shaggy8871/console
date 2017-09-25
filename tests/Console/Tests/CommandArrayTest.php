<?php
namespace Console\Tests;

use PHPUnit\Framework\TestCase;
use Console\Runner;
use Console\Exception\CommandNotFoundException;

class CommandArrayTest extends TestCase
{

    public function testCommandArray()
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
            'app:simple' => [
                'class' => 'Console\Tests\Commands\AppSimple'
            ]
        ]);
        // Run test
        $console->run();

    }

    public function testCommandArrayRegisterSingle()
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

    public function testCommandArrayWithLongArgument()
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
            'app:simple' => [
                'class' => 'Console\Tests\Commands\AppSimple'
            ]
        ]);
        // Run test
        $console->run();

    }

    public function testCommandArrayWithLongAndShortArgument()
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
            'app:simple' => [
                'class' => 'Console\Tests\Commands\AppSimple'
            ]
        ]);
        // Run test
        $console->run();

    }

    public function testCommandArrayWithStandaloneShortArgument()
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
            'app:simple' => [
                'class' => 'Console\Tests\Commands\AppSimple'
            ]
        ]);
        // Run test
        $console->run();

    }

    public function testCommandArrayWithAliases()
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
            'app:simple' => [
                'class' => 'Console\Tests\Commands\AppSimple'
            ]
        ]);
        // Run test
        $console->run();

    }

    public function testCommandArrayWithInvalidCommand()
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
            'app:simple' => [
                'class' => 'Console\Tests\Commands\AppSimple'
            ]
        ]);
        // Run test
        $console->run();

    }

    public function testCommandArrayWithNoCommand()
    {

        $console = new Runner();
        // Register handlers
        $console->registerAll([
            'app:simple' => [
                'class' => 'Console\Tests\Commands\AppSimple'
            ]
        ]);
        // Inject arguments
        $console->setArguments([
            'test.php'
        ]);

        $this->expectOutputString($console->getHelp());

        // Run test
        $console->run();

    }

    public function testCommandArrayWithDescription()
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

    public function testCommandArrayWithDescriptionRegisterSingle()
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