<?php

namespace Tests\PHP\Manipulator\ContainerConstraint;

use PHP\Manipulator\ContainerConstraint\Mock;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group ContainerConstraint_Mock
 */
class MockTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\ContainerConstraint\Mock
     */
    public function testDefaultConstruct()
    {
        $mock = new Mock();
    }

    /**
     * @covers \PHP\Manipulator\ContainerConstraint\Mock
     */
    public function testOptionViaConstructorWorks()
    {
        $mock = new Mock(array('return' => true));
        $container = new TokenContainer();
        $this->assertTrue($mock->evaluate($container));

        $mock = new Mock(array('return' => false));
        $container = new TokenContainer();
        $this->assertFalse($mock->evaluate($container));
    }

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