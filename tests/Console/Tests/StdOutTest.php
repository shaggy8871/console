<?php
namespace Console\Tests;

use PHPUnit\Framework\TestCase;
use Console\{StdOut, Decorate};

class StdOutTest extends TestCase
{

    public function testFormat()
    {
        $output = StdOut::format([
            ['Hello, world!', 'red']
        ]);
        $this->assertEquals($output, "\033[0;31mHello, world!\033[0m");
    }

    public function testWrite()
    {
        $this->expectOutputString("\033[0;31mHello, world!\033[0m");
        StdOut::write([
            ['Hello, world!', 'red']
        ]);
    }

    public function testFormatAnsiDisabled()
    {
        StdOut::disableAnsi();
        $output = StdOut::format([
            ['Hello, world!', 'red']
        ]);
        $this->assertEquals($output, "Hello, world!");
    }

    public function testFormatAnsiDisabledThenReEnabled()
    {
        StdOut::disableAnsi();
        StdOut::enableAnsi();
        $output = StdOut::format([
            ['Hello, world!', 'red']
        ]);
        $this->assertEquals($output, "\033[0;31mHello, world!\033[0m");
    }

    public function testAnsiEnabledByDefault()
    {
        $this->assertEquals(StdOut::isAnsiEnabled(), true);
    }

    public function testAnsiDisabledFlag()
    {
        StdOut::disableAnsi();

        $this->assertEquals(StdOut::isAnsiEnabled(), false);
    }

}