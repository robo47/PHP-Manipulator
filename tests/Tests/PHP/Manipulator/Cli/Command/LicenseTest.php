<?php

namespace Tests\PHP\Manipulator\Cli\Command;

use PHP\Manipulator\Cli\Command\License;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Cli\Command\License
 */
class LicenseTest extends TestCase
{
    public function testExecute()
    {
        $command       = new License();
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertContains('2010', $commandTester->getDisplay());
        $this->assertContains('2016', $commandTester->getDisplay());
        $this->assertContains('Benjamin Steininger', $commandTester->getDisplay());
        $this->assertContains('Robo47', $commandTester->getDisplay());
    }
}
