<?php

class PHP_Formatter_TokenConstraint_IsSinglelineCommentTest extends PHPFormatterTestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, "//")),
            true
        );

        #1
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, "/* */")),
            false
        );

        #2
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, "#")),
            true
        );

        #3
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_DOC_COMMENT, "/** */")),
            false
        );

        #4
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_ABSTRACT, "x\n")),
            false
        );

        #5
        $data[] = array(
            PHP_Formatter_Token::factory('//'),
            false
        );

        #6
        $data[] = array(
            PHP_Formatter_Token::factory('#'),
            false
        );

        #7
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, "// Foo")),
            true
        );

        #7
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, "# Foo")),
            true
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP_Formatter_TokenConstraint_IsSinglelineComment::evaluate
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new PHP_Formatter_TokenConstraint_IsSinglelineComment();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}