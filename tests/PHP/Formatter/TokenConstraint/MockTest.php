<?php

/**
 * @group TokenConstraint_Mock
 */
class PHP_Formatter_TokenConstraint_MockTest extends PHPFormatterTestCase
{

    /**
     * @covers PHP_Formatter_TokenConstraint_Mock
     */
    public function testDefaultConstruct()
    {
        $mock = new PHP_Formatter_TokenConstraint_Mock();
    }

    /**
     * @covers PHP_Formatter_TokenConstraint_Mock
     */
    public function testOptionViaConstructorWorks()
    {
        $mock = new PHP_Formatter_TokenConstraint_Mock(array('return' => true));
        $token = PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertTrue($mock->evaluate($token));

        $mock = new PHP_Formatter_TokenConstraint_Mock(array('return' => false));
        $token = PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertFalse($mock->evaluate($token));
    }

    /**
     * @covers PHP_Formatter_TokenConstraint_Mock
     */
    public function testOptionViastaticVariableWorks()
    {
        PHP_Formatter_TokenConstraint_Mock::$return = true;
        $mock = new PHP_Formatter_TokenConstraint_Mock();
        $token = PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertTrue($mock->evaluate($token));

        PHP_Formatter_TokenConstraint_Mock::$return = false;
        $mock = new PHP_Formatter_TokenConstraint_Mock();
        $token = PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n"));
        $this->assertFalse($mock->evaluate($token));
    }
}