<?php

require_once 'PHP/Formatter/TokenManipulator/UppercaseTokenValue.php';

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
            true,
            true
        );

        #1
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_BOOLEAN_OR, "or")),
            PHP_Formatter_Token::factory(array(T_BOOLEAN_OR, "OR")),
            true,
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers PHP_Formatter_TokenManipulator_UppercaseTokenValue::manipulate
     */
    public function testManipulate($token, $newToken, $changed, $strict)
    {
        $manipulator = new PHP_Formatter_TokenManipulator_UppercaseTokenValue();
        
        $this->assertSame($changed, $manipulator->manipulate($token), 'Wrong return value');
        $this->assertTokenMatch($token, $newToken, $strict);
    }
}