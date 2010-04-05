<?php

/**
 * @group TokenManipulator_Mock
 */
class PHP_Manipulator_TokenManipulator_MockTest extends TestCase
{

    /**
     * @covers PHP_Manipulator_TokenManipulator_Mock
     */
    public function testCallingManipulateSetsCalledToTrue()
    {
        PHP_Manipulator_TokenManipulator_Mock::$called = false;
        $mock = new PHP_Manipulator_TokenManipulator_Mock();
        $token = PHP_Manipulator_Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertFalse(PHP_Manipulator_TokenManipulator_Mock::$called);
        $mock->manipulate($token);
        $this->assertTrue(PHP_Manipulator_TokenManipulator_Mock::$called);
    }
}