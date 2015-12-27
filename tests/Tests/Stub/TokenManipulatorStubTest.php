<?php

namespace Tests\Stub;

use PHP\Manipulator\Token;
use Tests\TestCase;

/**
 * @covers Tests\Stub\TokenManipulatorStub
 */
class TokenManipulatorStubTest extends TestCase
{
    public function testCallingManipulateSetsCalledToTrue()
    {
        TokenManipulatorStub::$called = false;
        $stub                         = new TokenManipulatorStub();
        $token                        = Token::createFromMixed([T_WHITESPACE, "\n"]);
        $this->assertFalse(TokenManipulatorStub::$called);
        $stub->manipulate($token);
        $this->assertTrue(TokenManipulatorStub::$called);
    }
}
