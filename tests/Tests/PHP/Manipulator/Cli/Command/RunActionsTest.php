<?php

namespace Tests\PHP\Manipulator\Cli\Command;

use PHP\Manipulator\Cli\Command\RunActions;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Cli\Command\RunActions
 */
class RunActionsTest extends TestCase
{
    public function testExecute()
    {
        $this->markTestIncomplete('not implemented yet');

        $command       = new RunActions();
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $this->assertSame('something', $commandTester->getDisplay());
    }
}
