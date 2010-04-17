<?php

namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\ContainsOnlyWhitespace;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint\ContainsOnlyWhitespace
 */
class ContainsOnlyWhitespaceTest
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
            Token::factory(array(T_INLINE_HTML, "\n\t\r ")),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_INLINE_HTML, "a\n")),
            false
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers \PHP\Manipulator\TokenConstraint\ContainsOnlyWhitespace
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new ContainsOnlyWhitespace();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}