<?php

namespace Tests\PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli;
use PHP\Manipulator\Cli\Action\RunActions;

/**
 * @group Cli
 * @group Cli\Action
 * @group Cli\Action\RunActions
 */
class RunActionsTest extends \Tests\TestCase
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
     * @covers \PHP\Manipulator\Cli\Action\RunActions::run
     */
    public function testRun()
    {
        $this->markTestIncomplete('not implemented yet');
        $cli = new Cli();
        $action = new RunActions($cli);
        $action->run();
        $output = \ob_get_contents();
        $this->assertEquals('', $output);
    }

    /**
     * @covers \PHP\Manipulator\Cli\Action\RunActions::getConsoleOption
     */
    public function testGetConsoleOption()
    {
        $cli = new Cli();
        $action = new RunActions($cli);
        $consoleOptions = $action->getConsoleOption();

        $this->assertType('array', $consoleOptions);
        $this->assertCount(2, $consoleOptions);

        $this->assertType('\ezcConsoleOption', $consoleOptions[0]);
        $this->assertType('\ezcConsoleOption', $consoleOptions[1]);
        $this->markTestIncomplete('Test Values and stuff');
    }
}