<?php

require_once 'PHP/Formatter/TokenConstraint/EndsWithNewline.php';

class PHP_Formatter_TokenConstraint_EndsWithNewlineTest extends PHPFormatterTestCase
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
            true
        );

        #1
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n\r")),
            true
        );

        #2
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r")),
            true
        );

        #3
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "x\n")),
            true
        );

        #4
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "x\r\n")),
            true
        );

        #5
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "x\r")),
            true
        );

        #3
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "x\n ")),
            false
        );

        #4
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "x\r\n ")),
            false
        );

        #5
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "x\r ")),
            false
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP_Formatter_TokenConstraint_EndsWithNewline::evaluate
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new PHP_Formatter_TokenConstraint_EndsWithNewline();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}