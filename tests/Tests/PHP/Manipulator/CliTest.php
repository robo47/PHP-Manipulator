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
     * @covers \PHP\Manipulator\Cli::<protected>
     * @covers \PHP\Manipulator\Cli::run
     */
    public function testRun()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers \PHP\Manipulator\Cli::__construct
     * @covers \PHP\Manipulator\Cli::_initApp
     */
    public function testConstruct()
    {
        $cli = new Cli();
        $commands = $cli->all();
        // 2 defaults (helpCommand, listCommand)
        // 3 own (ShowTokens, License, RunActions)
        $this->assertCount(2 + 3, $commands);
    }
}
