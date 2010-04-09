<?php

namespace Tests\PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli;
use PHP\Manipulator\Cli\Action\ApplyRules;

/**
 * @group TokenContainerIterator
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
        ob_start();
        $cli = new Cli();
        $action = new ApplyRules($cli);
        $action->run();
        $output = \ob_get_contents();
        $this->assertEquals('', $output);
    }
}