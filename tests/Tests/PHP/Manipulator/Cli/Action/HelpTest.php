<?php

namespace Tests\PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli;
use PHP\Manipulator\Cli\Action\Help;

/**
 * @group Action\Help
 */
class HelpTest extends \Tests\TestCase
{
    
    public function setUp()
    {
        ob_start();
    }
    
    public function tearDown()
    {
        \ob_clean();
    }

    /**
     * @covers \PHP\Manipulator\Cli\Action\Help::run
     */
    public function testRun()
    {
        $this->markTestSkipped('not implemented yet');
        $cli = new Cli();
        $action = new Help($cli);
        $action->run();
        $output = \ob_get_contents();
        $this->assertEquals('', $output);
    }

    /**
     * @covers \PHP\Manipulator\Cli\Action\Help::getConsoleOption
     */
    public function testGetConsoleOption()
    {
        $cli = new Cli();
        $action = new Help($cli);
        $consoleOptions = $action->getConsoleOption();

        $this->assertType('array', $consoleOptions);
        $this->assertCount(1, $consoleOptions);

        $this->assertType('\ezcConsoleOption', $consoleOptions[0]);
        $this->markTestIncomplete('Test Values and stuff');
    }
}