<?php

/**
 * @group TokenConstraint_IsErrorControlOperator
 */
class PHP_Formatter_TokenConstraint_IsErrorControlOperatorTest extends TestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n")),
            false
        );

        #1
        $data[] = array(
            PHP_Formatter_Token::factory(array(null, '@')),
            true
        );

        #2
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, '@')),
            false
        );

        #3
        $data[] = array(
            PHP_Formatter_Token::factory(array(null, "\n")),
            false
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP_Formatter_TokenConstraint_IsErrorControlOperator
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new PHP_Formatter_TokenConstraint_IsErrorControlOperator();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}