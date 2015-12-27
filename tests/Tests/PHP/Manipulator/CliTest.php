<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\Cli;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Cli
 */
class CliTest extends TestCase
{
    public function setUp()
    {
        ob_start();
    }

    public function tearDown()
    {
        ob_end_clean();
    }

    public function testRun()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    public function testConstruct()
    {
        $cli      = new Cli();
        $commands = $cli->all();
        // 2 defaults (helpCommand, listCommand)
        // 3 own (ShowTokens, License, RunActions)
        $this->assertCount(2 + 3, $commands);
    }
}
