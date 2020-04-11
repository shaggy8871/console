<?php
namespace Console\Tests;

use PHPUnit\Framework\TestCase;
use Console\Args;

class ArgsTest extends TestCase
{

    public function testParse()
    {
        $args = new Args([
            'filename.php',
            '--longform="longform"',
            '-s',
            'shortform',
        ]);
        $this->assertEquals($args->longform, 'longform');
        $this->assertEquals($args->s, 'shortform');
    }

    public function testSetAliases()
    {
        $args = new Args([
            'filename.php',
            '-s',
            'shortform',
        ]);
        $args->setAliases([
            's' => 'shortform'
        ]);
        $this->assertEquals($args->s, null);
        $this->assertEquals($args->shortform, 'shortform');
    }

    public function testGetArgs()
    {
        $args = new Args([
            'filename.php',
            '--longform',
            '-s',
            'shortform'
        ]);
        $this->assertEquals($args->getArgs(), [
            'longform' => null,
            's' => 'shortform'
        ]);
    }

    public function testGetCommands()
    {
        $args = new Args([
            'filename.php',
            'app:run',
            'command:test',
        ]);
        $this->assertEquals($args->getCommands(), [
            'app:run',
            'command:test'
        ]);
    }

    public function testNullValueLongform()
    {
        $args = new Args([
            'filename.php',
            '--longform',
        ]);
        $this->assertEquals(isset($args->longform), true);
    }

}