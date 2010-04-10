<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator\Mock;
use PHP\Manipulator\Token;

/**
 * @group TokenManipulator_Mock
 */
class MockTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\TokenManipulator\Mock
     */
    public function testCallingManipulateSetsCalledToTrue()
    {
        Mock::$called = false;
        $mock = new Mock();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertFalse(Mock::$called);
        $mock->manipulate($token);
        $this->assertTrue(Mock::$called);
    }
}