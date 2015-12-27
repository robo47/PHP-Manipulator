<?php

namespace Tests\Stub;

use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers Tests\Stub\ActionStub
 */
class ActionStubTest extends TestCase
{
    public function testCallingManipulateSetsCalledToTrue()
    {
        ActionStub::$called = false;
        $stub               = new ActionStub();
        $container          = TokenContainer::createEmptyContainer();
        $this->assertFalse(ActionStub::$called);
        $stub->run($container);
        $this->assertTrue(ActionStub::$called);
    }
}
