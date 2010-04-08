<?php

namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\IsType;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint_IsType
 */
class IsTypeTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(T_COMMENT, "// some comment")),
            T_COMMENT,
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_COMMENT, "// some comment")),
            T_WHITESPACE,
            false
        );

        #2
        $data[] = array(
            Token::factory(array(T_COMMENT, "// some comment")),
            array(T_WHITESPACE, T_CLOSE_TAG, T_COMMENT),
            true
        );

        #3
        $data[] = array(
            Token::factory(array(T_COMMENT, "// some comment")),
            array(T_WHITESPACE, T_CLOSE_TAG, T_DOC_COMMENT),
            false
        );


        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers \PHP\Manipulator\TokenConstraint\IsType
     */
    public function testEvaluate($token, $param, $result)
    {
        $constraint = new IsType();
        $this->assertSame($result, $constraint->evaluate($token, $param), 'Wrong result');
    }
}