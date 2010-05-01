<?php

namespace Tests\Mock;

use Tests\Mock\ActionMock;
use PHP\Manipulator\TokenContainer;

/**
 * @group Mock
 * @group Mock\ActionMock
 */
class ActionMockTest extends \Tests\TestCase
{

    /**
     * @covers \Tests\Mock\ActionMock
     */
    public function testCallingManipulateSetsCalledToTrue()
    {
        ActionMock::$called = false;
        $mock = new ActionMock();
        $container = new TokenContainer();
        $this->assertFalse(ActionMock::$called);
        $mock->run($container);
        $this->assertTrue(ActionMock::$called);
    }
}