<?php

/**
 * @group TokenConstraint_IsMultilineComment
 */
class PHP_Formatter_TokenConstraint_IsMultilineCommentTest extends PHPFormatterTestCase
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
            false
        );

        #1
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, "/* */")),
            true
        );

        #2
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, "#")),
            false
        );

        #3
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_DOC_COMMENT, "/** */")),
            true
        );

        #4
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_ABSTRACT, "x\n")),
            false
        );

        #5
        $data[] = array(
            PHP_Formatter_Token::factory('/*'),
            false
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP_Formatter_TokenConstraint_IsMultilineComment::evaluate
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new PHP_Formatter_TokenConstraint_IsMultilineComment();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}