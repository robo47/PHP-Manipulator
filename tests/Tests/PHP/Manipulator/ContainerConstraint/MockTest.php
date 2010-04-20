<?php

namespace Tests\PHP\Manipulator\ContainerConstraint;

use PHP\Manipulator\ContainerConstraint\Mock;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group ContainerConstraint
 * @group ContainerConstraint\Mock
 */
class MockTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\ContainerConstraint\Mock
     */
    public function testOptionViastaticVariableWorks()
    {
        Mock::$return = true;
        $mock = new Mock();
        $container = new TokenContainer();
        $this->assertTrue($mock->evaluate($container));

        Mock::$return = false;
        $mock = new Mock();
        $container = new TokenContainer();
        $this->assertFalse($mock->evaluate($container));
    }
}