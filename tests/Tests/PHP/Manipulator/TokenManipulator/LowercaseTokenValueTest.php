<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator\LowercaseTokenValue;
use PHP\Manipulator\Token;

/**
 * @group TokenManipulator_LowercaseTokenValue
 */
class LowercaseTokenValueTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(T_BOOLEAN_AND, "AND")),
            Token::factory(array(T_BOOLEAN_AND, "and")),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_BOOLEAN_OR, "OR")),
            Token::factory(array(T_BOOLEAN_OR, "or")),
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers \PHP\Manipulator\TokenManipulator\LowercaseTokenValue::manipulate
     */
    public function testManipulate($actualToken, $expectedToken, $strict)
    {
        $manipulator = new LowercaseTokenValue();
        $manipulator->manipulate($actualToken);
        $this->assertTokenMatch($expectedToken, $actualToken, $strict);
    }
}