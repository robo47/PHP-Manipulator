<?php

namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\IsDoublequote;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint\IsDoublequote
 */
class IsDoublequoteTest
extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(null, '"')),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_WHITESPACE, '"')),
            false
        );

        #2
        $data[] = array(
            Token::factory(array(null, "'")),
            false
        );

        #3
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\n")),
            false
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers \PHP\Manipulator\TokenConstraint\IsDoublequote
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new IsDoublequote();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}
