<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator;
use PHP\Manipulator\Cli;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Cli
 */
class CliTest extends \Tests\TestCase
{
    public function setUp()
    {
        ob_start();
    }
    public function tearDown()
    {
        ob_end_clean();
    }

    /**
     * @covers \PHP\Manipulator\Cli::getStartTime
     */
    public function testGetStartTime()
    {
        $cli = new Cli();
        $this->assertType('float', $cli->getStartTime());
    }

    /**
     * @covers \PHP\Manipulator\Cli::<protected>
     * @covers \PHP\Manipulator\Cli::run
     */
    public function testRun()
    {
        $this->markTestIncomplete('not implemented yet');
    }
}