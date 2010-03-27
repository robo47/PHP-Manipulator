<?php

require_once 'PHP/Formatter/TokenManipulator/Mock.php';

class PHP_Formatter_TokenManipulator_MockTest extends PHPFormatterTestCase
{

    /**
     * @covers PHP_Formatter_TokenManipulator_Mock
     */
    public function testDefaultConstruct()
    {
        $mock = new PHP_Formatter_TokenManipulator_Mock();
    }

    /**
     * @covers PHP_Formatter_TokenManipulator_Mock
     */
    public function testOptionViaConstructorWorks()
    {
        $mock = new PHP_Formatter_TokenManipulator_Mock(array('return' => true));
        $token = PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertTrue($mock->manipulate($token));

        $mock = new PHP_Formatter_TokenManipulator_Mock(array('return' => false));
        $token = PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertFalse($mock->manipulate($token));
    }

    /**
     * @covers PHP_Formatter_TokenManipulator_Mock
     */
    public function testOptionViastaticVariableWorks()
    {
        PHP_Formatter_TokenManipulator_Mock::$return = true;
        $mock = new PHP_Formatter_TokenManipulator_Mock();
        $token = PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertTrue($mock->manipulate($token));

        PHP_Formatter_TokenManipulator_Mock::$return = false;
        $mock = new PHP_Formatter_TokenManipulator_Mock();
        $token = PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertFalse($mock->manipulate($token));
    }
}