<?php

namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\IsColon;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint\IsColon
 */
class IsColonTest
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
            Token::factory(array(null, ':')),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_WHITESPACE, ':')),
            false
        );

        #2
        $data[] = array(
            Token::factory(array(null, ';')),
            false
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers \PHP\Manipulator\TokenConstraint\IsColon
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new IsColon();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}