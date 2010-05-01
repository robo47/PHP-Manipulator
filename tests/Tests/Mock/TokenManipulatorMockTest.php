<?php

namespace Tests\Mock;

use Tests\Mock\TokenManipulatorMock;
use PHP\Manipulator\TokenManipulator\Mock;
use PHP\Manipulator\Token;

/**
 * @group Mock
 * @group Mock\TokenManipulatorMock
 */
class TokenManipulatorMockTest extends \Tests\TestCase
{

    /**
     * @covers \Tests\Mock\TokenManipulatorMock
     */
    public function testCallingManipulateSetsCalledToTrue()
    {
        TokenManipulatorMock::$called = false;
        $mock = new TokenManipulatorMock();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertFalse(TokenManipulatorMock::$called);
        $mock->manipulate($token);
        $this->assertTrue(TokenManipulatorMock::$called);
    }
}