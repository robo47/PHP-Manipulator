<?php

namespace Tests\Mock;

use Tests\Mock\ContainerConstraintMock;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Mock
 * @group Mock\ContainerConstraintMock
 */
class ContainerConstraintMockTest
extends \Tests\TestCase
{

    /**
     * @covers \Tests\Mock\ContainerConstraintMock
     */
    public function testOptionViastaticVariableWorks()
    {
        ContainerConstraintMock::$return = true;
        $mock = new ContainerConstraintMock();
        $container = new TokenContainer();
        $this->assertTrue($mock->evaluate($container));

        ContainerConstraintMock::$return = false;
        $mock = new ContainerConstraintMock();
        $container = new TokenContainer();
        $this->assertFalse($mock->evaluate($container));
    }
}