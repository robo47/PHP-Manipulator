<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator\UppercaseTokenValue;
use PHP\Manipulator\Token;

/**
 * @group TokenFinder
 * @group TokenFinder\UppercaseTokenValue
 */
class UppercaseTokenValueTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(T_BOOLEAN_AND, 'and')),
            Token::factory(array(T_BOOLEAN_AND, 'AND')),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_BOOLEAN_OR, 'or')),
            Token::factory(array(T_BOOLEAN_OR, 'OR')),
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers \PHP\Manipulator\TokenManipulator\UppercaseTokenValue::manipulate
     */
    public function testManipulate($actualToken, $expectedToken, $strict)
    {
        $manipulator = new UppercaseTokenValue();
        $manipulator->manipulate($actualToken);
        $this->assertTokenMatch($expectedToken, $actualToken, $strict);
    }
}
