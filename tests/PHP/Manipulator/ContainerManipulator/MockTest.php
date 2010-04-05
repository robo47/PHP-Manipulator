<?php
namespace Tests\PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator\Mock;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group ContainerManipulator_Mock
 */
class MockTest extends \Tests\TestCase
{

    /**
     * @covers PHP\Manipulator\ContainerManipulator\Mock
     */
    public function testCallingManipulateSetsCalledToTrue()
    {
        Mock::$called = false;
        $mock = new Mock();
        $container = new TokenContainer();
        $this->assertFalse(Mock::$called);
        $mock->manipulate($container);
        $this->assertTrue(Mock::$called);
    }
}