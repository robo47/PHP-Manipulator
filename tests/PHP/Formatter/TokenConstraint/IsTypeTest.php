<?php

class PHP_Formatter_TokenConstraint_IsTypeTest extends PHPFormatterTestCase
{
    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, "// some comment")),
            T_COMMENT,
            true
        );

        #1
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, "// some comment")),
            T_WHITESPACE,
            false
        );

        #2
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, "// some comment")),
            array(T_WHITESPACE, T_CLOSE_TAG, T_COMMENT),
            true
        );

        #3
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, "// some comment")),
            array(T_WHITESPACE, T_CLOSE_TAG, T_DOC_COMMENT),
            false
        );


        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP_Formatter_TokenConstraint_IsType::evaluate
     */
    public function testEvaluate($token, $param, $result)
    {
        $constraint = new PHP_Formatter_TokenConstraint_IsType();
        $this->assertSame($result, $constraint->evaluate($token, $param), 'Wrong result');
    }
}