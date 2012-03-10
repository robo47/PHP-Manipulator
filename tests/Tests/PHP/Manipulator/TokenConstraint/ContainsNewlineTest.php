<?php

namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\ContainsNewline;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint\ContainsNewline
 */
class ContainsNewlineTest
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
            Token::factory(array(T_WHITESPACE, " \n ")),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "  ")),
            false
        );

        #2
        $data[] = array(
            Token::factory(array(T_WHITESPACE, " \r ")),
            true
        );

        #3
        $data[] = array(
            Token::factory(array(T_WHITESPACE, " \r\n ")),
            true
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers \PHP\Manipulator\TokenConstraint\ContainsNewline
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new ContainsNewline();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}
