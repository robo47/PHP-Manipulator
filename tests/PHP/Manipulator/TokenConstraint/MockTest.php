<?php

namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\Mock;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint_Mock
 */
class MockTest extends \Tests\TestCase
{

    /**
     * @covers PHP\Manipulator\TokenConstraint\Mock
     */
    public function testDefaultConstruct()
    {
        $mock = new Mock();
    }

    /**
     * @covers PHP\Manipulator\TokenConstraint\Mock
     */
    public function testOptionViaConstructorWorks()
    {
        $mock = new Mock(array('return' => true));
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertTrue($mock->evaluate($token));

        $mock = new Mock(array('return' => false));
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertFalse($mock->evaluate($token));
    }

    /**
     * @covers PHP\Manipulator\TokenConstraint\Mock
     */
    public function testOptionViastaticVariableWorks()
    {
        Mock::$return = true;
        $mock = new Mock();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertTrue($mock->evaluate($token));

        Mock::$return = false;
        $mock = new Mock();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertFalse($mock->evaluate($token));
    }
}