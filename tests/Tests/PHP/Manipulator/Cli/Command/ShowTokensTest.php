<?php

namespace Tests\PHP\Manipulator\Cli\Command;

use PHP\Manipulator\Cli;
use PHP\Manipulator\Cli\Command\ShowTokens;

/**
 * @group Cli
 * @group Cli\Command
 * @group Cli\Command\ShowTokens
 */
class ShowTokensTest extends \Tests\TestCase
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
     * @covers PHP\Manipulator\Cli\Command\ShowTokens::execute
     */
    public function testExecute()
    {
        $this->markTestIncomplete('not implemented yet');
        $command = new ShowTokens();
        $command->execute(new ArgvInput(array()), new StreamOutput(fopen('php://output', 'w')));
        $output = ob_get_contents();
        $this->assertEquals('', $output);
    }
}