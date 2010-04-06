<?php

namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\IsErrorControlOperator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint_IsErrorControlOperator
 */
class IsErrorControlOperatorTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\n")),
            false
        );

        #1
        $data[] = array(
            Token::factory(array(null, '@')),
            true
        );

        #2
        $data[] = array(
            Token::factory(array(T_WHITESPACE, '@')),
            false
        );

        #3
        $data[] = array(
            Token::factory(array(null, "\n")),
            false
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP\Manipulator\TokenConstraint\IsErrorControlOperator
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new IsErrorControlOperator();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}