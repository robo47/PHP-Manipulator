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
        $this->setUseOutputBuffering(true);
    }

    public function tearDown()
    {
        $this->setUseOutputBuffering(false);
    }

    /**
     * @covers \PHP\Manipulator\Cli\Action\ShowTokens
     */
    public function testConstructor()
    {
        $cli = new Cli();
        $action = new ShowTokens($cli);
    }
}