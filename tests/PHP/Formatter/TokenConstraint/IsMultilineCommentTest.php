<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';
require_once 'PHP/Formatter/TokenConstraint/IsMultilineComment.php';

class PHP_Formatter_TokenConstraint_IsMultilineCommentTest extends PHPFormatterTestCase
{
    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, "//")),
            false
        );

        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, "/*")),
            true
        );

        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, "#")),
            false
        );

        $data[] = array(
            PHP_Formatter_Token::factory(array(T_DOC_COMMENT, "/**")),
            true
        );

        $data[] = array(
            PHP_Formatter_Token::factory(array(T_ABSTRACT, "x\n")),
            false
        );

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