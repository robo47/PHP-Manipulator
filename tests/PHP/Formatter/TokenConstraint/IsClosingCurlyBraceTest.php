<?php

require_once 'PHP/Formatter/TokenConstraint/IsClosingCurlyBrace.php';

class PHP_Formatter_TokenConstraint_IsClosingCurlyBraceTest extends PHPFormatterTestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            PHP_Formatter_Token::factory(array(null, '{')),
            false
        );

        #1
        $data[] = array(
            PHP_Formatter_Token::factory(array(null, '}')),
            true
        );

        #2
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, '{')),
            false
        );

        #3
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, '}')),
            false
        );


        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP_Formatter_TokenConstraint_IsClosingCurlyBrace::evaluate
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new PHP_Formatter_TokenConstraint_IsClosingCurlyBrace();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}