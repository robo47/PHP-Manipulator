<?php

namespace Tests\Stub;

use Tests\Stub\TokenConstraintStub;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Stub
 * @group Stub\TokenConstraintStub
 */
class TokenConstraintStubTest extends \Tests\TestCase
{

    /**
     * @covers \Tests\Stub\TokenConstraintStub
     */
    public function testOptionViastaticVariableWorks()
    {
        TokenConstraintStub::$return = true;
        $stub = new TokenConstraintStub();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertTrue($stub->evaluate($token));

        TokenConstraintStub::$return = false;
        $stub = new TokenConstraintStub();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertFalse($stub->evaluate($token));
    }
}
