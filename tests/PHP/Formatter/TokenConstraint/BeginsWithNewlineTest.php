<?php

require_once 'PHP/Formatter/TokenConstraint/BeginsWithNewline.php';

class PHP_Formatter_TokenConstraint_BeginsWithNewlineTest extends PHPFormatterTestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n")),
            true
        );

        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n\r")),
            true
        );

        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r")),
            true
        );

        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "x\n")),
            false
        );

        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "x\n\r")),
            false
        );

        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "x\r")),
            false
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP_Formatter_TokenConstraint_BeginsWithNewline::evaluate
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new PHP_Formatter_TokenConstraint_BeginsWithNewline();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}