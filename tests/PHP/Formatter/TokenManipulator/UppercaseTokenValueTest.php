<?php

/**
 * @group TokenManipulator_UppercaseTokenValue
 */
class PHP_Formatter_TokenManipulator_UppercaseTokenValueTest extends PHPFormatterTestCase
{

    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_BOOLEAN_AND, "and")),
            PHP_Formatter_Token::factory(array(T_BOOLEAN_AND, "AND")),
            true
        );

        #1
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_BOOLEAN_OR, "or")),
            PHP_Formatter_Token::factory(array(T_BOOLEAN_OR, "OR")),
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers PHP_Formatter_TokenManipulator_UppercaseTokenValue::manipulate
     */
    public function testManipulate($token, $newToken, $strict)
    {
        $manipulator = new PHP_Formatter_TokenManipulator_UppercaseTokenValue();
        $manipulator->manipulate($token);
        $this->assertTokenMatch($token, $newToken, $strict);
    }
}