<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenManipulator\UppercaseTokenValue;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenManipulator\UppercaseTokenValue
 */
class UppercaseTokenValueTest extends TestCase
{
    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([T_BOOLEAN_AND, 'and']),
            Token::createFromMixed([T_BOOLEAN_AND, 'AND']),
        ];

        #1
        $data[] = [
            Token::createFromMixed([T_BOOLEAN_OR, 'or']),
            Token::createFromMixed([T_BOOLEAN_OR, 'OR']),
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
        $manipulator = new UppercaseTokenValue();
        $manipulator->manipulate($actualToken);
        $this->assertTokenMatch($expectedToken, $actualToken, true);
    }
}
