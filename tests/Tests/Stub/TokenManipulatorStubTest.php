<?php

namespace Tests\Stub;

use Tests\Stub\TokenManipulatorStub;
use PHP\Manipulator\TokenManipulator\Stub;
use PHP\Manipulator\Token;

/**
 * @group Stub
 * @group Stub\TokenManipulatorStub
 */
class TokenManipulatorStubTest extends \Tests\TestCase
{

    /**
     * @covers \Tests\Stub\TokenManipulatorStub
     */
    public function testCallingManipulateSetsCalledToTrue()
    {
        TokenManipulatorStub::$called = false;
        $stub = new TokenManipulatorStub();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertFalse(TokenManipulatorStub::$called);
        $stub->manipulate($token);
        $this->assertTrue(TokenManipulatorStub::$called);
    }
}