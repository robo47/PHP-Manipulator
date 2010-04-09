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
        $this->setUseOutputBuffering(true);
    }

    public function tearDown()
    {
        $this->setUseOutputBuffering(false);
    }

    /**
     * @covers \PHP\Manipulator\Cli\Action\ApplyRules::__construct
     */
    public function testConstructor()
    {
        $cli = new Cli();
        $action = new ApplyRules($cli);
    }
}