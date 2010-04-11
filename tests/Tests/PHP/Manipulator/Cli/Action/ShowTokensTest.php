<?php

namespace Tests\PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli;
use PHP\Manipulator\Cli\Action\ShowTokens;

/**
 * @group Action\ShowTokens
 */
class ShowTokensTest extends \Tests\TestCase
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
     * @covers \PHP\Manipulator\Cli\Action\ShowTokens::run
     */
    public function testRun()
    {
        $this->markTestSkipped('not implemented yet');
        $cli = new Cli();
        $action = new ShowTokens($cli);
        $action->run();
        $output = \ob_get_contents();
        $this->assertEquals('', $output);
    }

    /**
     * @covers \PHP\Manipulator\Cli\Action\ShowTokens::getConsoleOption
     */
    public function testGetConsoleOption()
    {
        $cli = new Cli();
        $action = new ShowTokens($cli);
        $consoleOptions = $action->getConsoleOption();

        $this->assertType('array', $consoleOptions);
        $this->assertCount(1, $consoleOptions);

        $this->assertType('\ezcConsoleOption', $consoleOptions[0]);
        $this->markTestIncomplete('Test Values and stuff');
    }
}