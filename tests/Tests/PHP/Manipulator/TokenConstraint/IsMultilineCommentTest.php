<?php

namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\IsMultilineComment;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint
 * @group TokenConstraint\IsMultilineComment
 */
class IsMultilineCommentTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(T_COMMENT, "//")),
            false
        );

        #1
        $data[] = array(
            Token::factory(array(T_COMMENT, "/* */")),
            true
        );

        #2
        $data[] = array(
            Token::factory(array(T_COMMENT, "#")),
            false
        );

        #3
        $data[] = array(
            Token::factory(array(T_DOC_COMMENT, "/** */")),
            true
        );

        #4
        $data[] = array(
            Token::factory(array(T_ABSTRACT, "x\n")),
            false
        );

        #5
        $data[] = array(
            Token::factory(array(T_DOC_COMMENT, "/**\n* My class Foo\n*/")),
            true
        );

        #6
        $data[] = array(
            Token::factory(array(T_COMMENT, "/*\n* My class Foo\n*/")),
            true
        );

        #7
        $data[] = array(
            Token::factory('/*'),
            false
        );


        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers \PHP\Manipulator\TokenConstraint\IsMultilineComment
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new IsMultilineComment();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}
