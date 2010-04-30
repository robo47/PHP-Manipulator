<?php

namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\IsComma;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint\IsComma
 */
class IsCommaTest
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
            Token::factory(array(null, ',')),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_WHITESPACE, ',')),
            false
        );

        #2
        $data[] = array(
            Token::factory(array(null, ':')),
            false
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers \PHP\Manipulator\TokenConstraint\IsComma
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new IsComma();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}