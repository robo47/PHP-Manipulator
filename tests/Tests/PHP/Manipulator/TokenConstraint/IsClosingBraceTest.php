<?php

namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\IsClosingBrace;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint_IsClosingBrace
 */
class IsClosingBraceTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(null, '(')),
            false
        );

        #1
        $data[] = array(
            Token::factory(array(null, ')')),
            true
        );

        #2
        $data[] = array(
            Token::factory(array(T_COMMENT, '(')),
            false
        );

        #3
        $data[] = array(
            Token::factory(array(T_COMMENT, ')')),
            false
        );


        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers \PHP\Manipulator\TokenConstraint\IsClosingBrace::evaluate
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new IsClosingBrace();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}