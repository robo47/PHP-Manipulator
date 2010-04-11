<?php

namespace Tests\PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli;
use PHP\Manipulator;
use PHP\Manipulator\Cli\Action\Stats;

/**
 * @group Cli
 * @group Cli\Action
 * @group Cli\Action\Stats
 */
class StatsTest extends \Tests\TestCase
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
     * @covers \PHP\Manipulator\Cli\Action\Stats::run
     */
    public function testRun()
    {
        $cli = new Cli();
        $action = new Stats($cli);
        $action->run();
        $output = \ob_get_contents();

        $footer = '~Time: (\d+)s' . PHP_EOL;
        $footer .= 'Memory: (\d+).(\d+)kb' . PHP_EOL . '~';
        $match = preg_match($footer, $output);
        $this->assertTrue(false !== $match);
    }

    /**
     * @covers \PHP\Manipulator\Cli\Action\Stats::getConsoleOption
     */
    public function testGetConsoleOption()
    {
        $cli = new Cli();
        $action = new Stats($cli);
        $consoleOptions = $action->getConsoleOption();

        $this->assertType('array', $consoleOptions);
        $this->assertCount(1, $consoleOptions);

        $this->assertType('\ezcConsoleOption', $consoleOptions[0]);
        $this->markTestIncomplete('Test Values and stuff');
    }
}
