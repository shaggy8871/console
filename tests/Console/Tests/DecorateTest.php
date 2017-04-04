<?php
namespace Console\Tests;

use PHPUnit\Framework\TestCase;
use Console\Decorate;

class DecorateTest extends TestCase
{

    public function testColorIsRed()
    {

        $this->expectOutputString("\033[0;31mHello, world!\033[0m");

        echo Decorate::color('Hello, world!', 'red');

    }

    public function testColorIsBoldRed()
    {

        $this->expectOutputString("\033[1;31mHello, world!\033[0m");

        echo Decorate::color('Hello, world!', 'bold_red');

    }

    public function testColorIsBoldRedWithWhiteBG()
    {

        $this->expectOutputString("\033[1;31m\033[107mHello, world!\033[0m");

        echo Decorate::color('Hello, world!', 'bold red bg_white');

    }

}