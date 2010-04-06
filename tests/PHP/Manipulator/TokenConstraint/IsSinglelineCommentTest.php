<?php

namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\IsSinglelineComment;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint_IsSinglelineComment
 */
class IsSinglelineCommentTest extends \Tests\TestCase
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
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_COMMENT, "/* */")),
            false
        );

        #2
        $data[] = array(
            Token::factory(array(T_COMMENT, "#")),
            true
        );

        #3
        $data[] = array(
            Token::factory(array(T_DOC_COMMENT, "/** */")),
            false
        );

        #4
        $data[] = array(
            Token::factory(array(T_ABSTRACT, "x\n")),
            false
        );

        #5
        $data[] = array(
            Token::factory('//'),
            false
        );

        #6
        $data[] = array(
            Token::factory('#'),
            false
        );

        #7
        $data[] = array(
            Token::factory(array(T_COMMENT, "// Foo")),
            true
        );

        #7
        $data[] = array(
            Token::factory(array(T_COMMENT, "# Foo")),
            true
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP\Manipulator\TokenConstraint\IsSinglelineComment
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new IsSinglelineComment();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}