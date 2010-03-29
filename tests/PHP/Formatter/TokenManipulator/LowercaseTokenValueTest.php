<?php

require_once 'PHP/Formatter/TokenManipulator/LowercaseTokenValue.php';

class PHP_Formatter_TokenManipulator_LowercaseTokenValueTest extends PHPFormatterTestCase
{
    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_BOOLEAN_AND, "AND")),
            PHP_Formatter_Token::factory(array(T_BOOLEAN_AND, "and")),
            true,
            true
        );

        #1
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_BOOLEAN_OR, "OR")),
            PHP_Formatter_Token::factory(array(T_BOOLEAN_OR, "or")),
            true,
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers PHP_Formatter_TokenManipulator_LowercaseTokenValue::manipulate
     */
    public function testManipulate($token, $newToken, $changed, $strict)
    {
        $manipulator = new PHP_Formatter_TokenManipulator_LowercaseTokenValue();
        
        $this->assertSame($changed, $manipulator->manipulate($token), 'Wrong return value');
        $this->assertTokenMatch($token, $newToken, $strict);
    }
}