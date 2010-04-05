<?php

/**
 * @group TokenManipulator_Mock
 */
class PHP_Formatter_TokenManipulator_MockTest extends TestCase
{

    /**
     * @covers PHP_Formatter_TokenManipulator_Mock
     */
    public function testCallingManipulateSetsCalledToTrue()
    {
        PHP_Formatter_TokenManipulator_Mock::$called = false;
        $mock = new PHP_Formatter_TokenManipulator_Mock();
        $token = PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertFalse(PHP_Formatter_TokenManipulator_Mock::$called);
        $mock->manipulate($token);
        $this->assertTrue(PHP_Formatter_TokenManipulator_Mock::$called);
    }
}