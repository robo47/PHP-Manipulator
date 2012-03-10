<?php

namespace Tests\Stub;

use Tests\Stub\ActionStub;
use PHP\Manipulator\TokenContainer;

/**
 * @group Stub
 * @group Stub\ActionStub
 */
class ActionStubTest extends \Tests\TestCase
{

    /**
     * @covers \Tests\Stub\ActionStub
     */
    public function testCallingManipulateSetsCalledToTrue()
    {
        ActionStub::$called = false;
        $stub = new ActionStub();
        $container = new TokenContainer();
        $this->assertFalse(ActionStub::$called);
        $stub->run($container);
        $this->assertTrue(ActionStub::$called);
    }
}
