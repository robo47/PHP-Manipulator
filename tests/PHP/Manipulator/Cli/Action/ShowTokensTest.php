<?php

namespace Tests\PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli;
use PHP\Manipulator\Cli\Action\ShowTokens;

/**
 * @group TokenContainerIterator
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
}