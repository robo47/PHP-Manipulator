<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\Mock;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\Mock
 */
class MockTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\Mock
     */
    public function testCallingManipulateSetsCalledToTrue()
    {
        Mock::$called = false;
        $mock = new Mock();
        $container = new TokenContainer();
        $this->assertFalse(Mock::$called);
        $mock->run($container);
        $this->assertTrue(Mock::$called);
    }
}