<?php

namespace Tests\Mock;

use Tests\Mock\TokenConstraintMock;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Mock
 * @group Mock\TokenConstraintMock
 */
class TokenConstraintMockTest extends \Tests\TestCase
{

    /**
     * @covers \Tests\Mock\TokenConstraintMock
     */
    public function testOptionViastaticVariableWorks()
    {
        TokenConstraintMock::$return = true;
        $mock = new TokenConstraintMock();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertTrue($mock->evaluate($token));

        TokenConstraintMock::$return = false;
        $mock = new TokenConstraintMock();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertFalse($mock->evaluate($token));
    }
}