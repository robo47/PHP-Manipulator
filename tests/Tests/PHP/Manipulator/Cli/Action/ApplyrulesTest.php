<?php

namespace Tests\PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli;
use PHP\Manipulator\Cli\Action\ApplyRules;

/**
 * @group Action\ApplyRules
 */
class ApplyrulesTest extends \Tests\TestCase
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
     * @covers \PHP\Manipulator\Cli\Action\ApplyRules::run
     */
    public function testRun()
    {
        $this->markTestSkipped('not implemented yet');
        $cli = new Cli();
        $action = new ApplyRules($cli);
        $action->run();
        $output = \ob_get_contents();
        $this->assertEquals('', $output);
    }

    /**
     * @covers \PHP\Manipulator\Cli\Action\ApplyRules::getConsoleOption
     */
    public function testGetConsoleOption()
    {
        $cli = new Cli();
        $action = new ApplyRules($cli);
        $consoleOptions = $action->getConsoleOption();

        $this->assertType('array', $consoleOptions);
        $this->assertCount(2, $consoleOptions);

        $this->assertType('\ezcConsoleOption', $consoleOptions[0]);
        $this->assertType('\ezcConsoleOption', $consoleOptions[1]);
        $this->markTestIncomplete('Test Values and stuff');
    }
}