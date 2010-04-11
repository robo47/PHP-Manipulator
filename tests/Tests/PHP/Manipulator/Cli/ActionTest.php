<?php

namespace Tests\PHP\Manipulator\Cli;

use PHP\Manipulator\Cli;
use PHP\Manipulator\Cli\Action;

class NonAbstractAction extends Action
{
    
    public function getInfo()
    {
        return 'getInfo()';
    }
    
    public function run()
    {

    }
    
    public function getConsoleOption()
    {
        return array();
    }
}

/**
 * @group Cli
 * @group Cli\Action
 */
class ActionTest extends \Tests\TestCase
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
     * @covers \PHP\Manipulator\Cli\Action
     */
    public function testConstructorAndGetCli()
    {
        $cli = new Cli();
        $action = new NonAbstractAction($cli);
        $this->assertSame($cli, $action->getCli());
    }
}