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

    public function testVgaForegroundBrightRed()
    {

        $this->expectOutputString("\033[0;31m\033[38;5;9mHello, world!\033[0m");

        echo Decorate::color('Hello, world!', 'red ' . Decorate::vgaColor(9));

    }

    public function testVgaBackgroundBrightRed()
    {

        $this->expectOutputString("\033[0;31m\033[48;5;9mHello, world!\033[0m");

        echo Decorate::color('Hello, world!', 'red ' . Decorate::vgaBackground(9));

    }

    public function testBeep()
    {

        $this->expectOutputString("\007");

        echo Decorate::beep();

    }

    public function testCursorMovement()
    {

        $this->expectOutputString("\033[D\033[C");

        echo Decorate::cursorBackward();
        echo Decorate::cursorForward();

    }

    public function testMoveToCol()
    {

        $this->expectOutputString("\033[10G\033[0G");

        echo Decorate::cursorToCol(10);
        echo Decorate::cursorToCol(0);

    }

    public function testMoveToRow()
    {

        $this->expectOutputString("\033[10A\033[10B");

        echo Decorate::cursorUp(10);
        echo Decorate::cursorDown(10);

    }

    public function testCursorTo()
    {

        $this->expectOutputString("\033[s\033[10;10H\033[u");

        echo Decorate::saveCursorPos();
        echo Decorate::cursorTo(10,10);
        echo Decorate::restoreCursorPos();

    }

    public function testOverwriteLine()
    {

        $this->expectOutputString("Hello world!\rTest\033[K");

        echo 'Hello world!';
        echo Decorate::moveToStart();
        echo 'Test';
        echo Decorate::clearToEnd();

    }

}