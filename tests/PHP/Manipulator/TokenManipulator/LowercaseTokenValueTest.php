<?php

/**
 * @group TokenManipulator_LowercaseTokenValue
 */
class PHP_Manipulator_TokenManipulator_LowercaseTokenValueTest extends TestCase
{

    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            PHP_Manipulator_Token::factory(array(T_BOOLEAN_AND, "AND")),
            PHP_Manipulator_Token::factory(array(T_BOOLEAN_AND, "and")),
            true
        );

        #1
        $data[] = array(
            PHP_Manipulator_Token::factory(array(T_BOOLEAN_OR, "OR")),
            PHP_Manipulator_Token::factory(array(T_BOOLEAN_OR, "or")),
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers PHP_Manipulator_TokenManipulator_LowercaseTokenValue::manipulate
     */
    public function testManipulate($token, $newToken, $strict)
    {
        $manipulator = new PHP_Manipulator_TokenManipulator_LowercaseTokenValue();
        $manipulator->manipulate($token);
        $this->assertTokenMatch($token, $newToken, $strict);
    }
}