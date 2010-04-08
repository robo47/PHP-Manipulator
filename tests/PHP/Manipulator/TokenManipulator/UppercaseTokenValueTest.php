<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator\UppercaseTokenValue;
use PHP\Manipulator\Token;

/**
 * @group TokenManipulator_UppercaseTokenValue
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
            Token::factory(array(T_BOOLEAN_AND, "and")),
            Token::factory(array(T_BOOLEAN_AND, "AND")),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_BOOLEAN_OR, "or")),
            Token::factory(array(T_BOOLEAN_OR, "OR")),
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers \PHP\Manipulator\TokenManipulator\UppercaseTokenValue::manipulate
     */
    public function testManipulate($token, $newToken, $strict)
    {
        $manipulator = new UppercaseTokenValue();
        $manipulator->manipulate($token);
        $this->assertTokenMatch($token, $newToken, $strict);
    }
}