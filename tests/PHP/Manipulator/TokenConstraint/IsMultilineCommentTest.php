<?php

/**
 * @group TokenConstraint_IsMultilineComment
 */
class PHP_Manipulator_TokenConstraint_IsMultilineCommentTest extends TestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            PHP_Manipulator_Token::factory(array(T_COMMENT, "//")),
            false
        );

        #1
        $data[] = array(
            PHP_Manipulator_Token::factory(array(T_COMMENT, "/* */")),
            true
        );

        #2
        $data[] = array(
            PHP_Manipulator_Token::factory(array(T_COMMENT, "#")),
            false
        );

        #3
        $data[] = array(
            PHP_Manipulator_Token::factory(array(T_DOC_COMMENT, "/** */")),
            true
        );

        #4
        $data[] = array(
            PHP_Manipulator_Token::factory(array(T_ABSTRACT, "x\n")),
            false
        );

        #5
        $data[] = array(
            PHP_Manipulator_Token::factory(array(T_DOC_COMMENT, "/**\n* My class Foo\n*/")),
            true
        );

        #6
        $data[] = array(
            PHP_Manipulator_Token::factory(array(T_COMMENT, "/*\n* My class Foo\n*/")),
            true
        );

        #7
        $data[] = array(
            PHP_Manipulator_Token::factory('/*'),
            false
        );



        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP_Manipulator_TokenConstraint_IsMultilineComment::evaluate
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new PHP_Manipulator_TokenConstraint_IsMultilineComment();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}