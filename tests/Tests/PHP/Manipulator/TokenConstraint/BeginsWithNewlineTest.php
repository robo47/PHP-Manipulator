<?php

namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\BeginsWithNewline;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint
 * @group TokenConstraint\BeginsWithNewline
 */
class BeginsWithNewlineTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\n")),
            true
        );

        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\n\r")),
            true
        );

        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\r")),
            true
        );

        $data[] = array(
            Token::factory(array(T_WHITESPACE, "x\n")),
            false
        );

        $data[] = array(
            Token::factory(array(T_WHITESPACE, "x\n\r")),
            false
        );

        $data[] = array(
            Token::factory(array(T_WHITESPACE, "x\r")),
            false
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers \PHP\Manipulator\TokenConstraint\BeginsWithNewline::evaluate
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new BeginsWithNewline();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}