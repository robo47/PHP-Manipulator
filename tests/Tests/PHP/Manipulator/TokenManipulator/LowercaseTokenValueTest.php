<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenManipulator\LowercaseTokenValue;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenManipulator\LowercaseTokenValue::manipulate
 */
class LowercaseTokenValueTest extends TestCase
{
    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([T_BOOLEAN_AND, 'AND']),
            Token::createFromMixed([T_BOOLEAN_AND, 'and']),
        ];

        #1
        $data[] = [
            Token::createFromMixed([T_BOOLEAN_OR, 'OR']),
            Token::createFromMixed([T_BOOLEAN_OR, 'or']),
        ];

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     *
     * @param Token $actualToken
     * @param Token $expectedToken
     */
    public function testManipulate(Token $actualToken, Token $expectedToken)
    {
        $manipulator = new LowercaseTokenValue();
        $manipulator->manipulate($actualToken);
        $this->assertTokenMatch($expectedToken, $actualToken, true);
    }
}
