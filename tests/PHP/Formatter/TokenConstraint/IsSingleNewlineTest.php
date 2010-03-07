<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';
require_once 'PHP/Formatter/TokenConstraint/IsSingleNewline.php';

class PHP_Formatter_TokenConstraint_IsSingleNewlineTest extends PHPFormatterTestCase
{
    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        # 0
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n")),
            true
        );

        # 1
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r")),
            true
        );

        # 2
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r\n")),
            true
        );

        # 3
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n\n")),
            false
        );

        # 4
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n\r")),
            false
        );

        # 5
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, " \n")),
            false
        );

        # 6
        $data[] = array(
            PHP_Formatter_Token::factory("\n"),
            true
        );

        #7
        $data[] = array(
            PHP_Formatter_Token::factory("\r\n"),
            true
        );

        #8
        $data[] = array(
            PHP_Formatter_Token::factory("\r"),
            true
        );

        #9
        $data[] = array(
            PHP_Formatter_Token::factory("\n\r"),
            false
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP_Formatter_TokenConstraint_IsSingleNewline::evaluate
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new PHP_Formatter_TokenConstraint_IsSingleNewline();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}