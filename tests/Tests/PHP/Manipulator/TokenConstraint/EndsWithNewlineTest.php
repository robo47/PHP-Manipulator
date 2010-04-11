<?php

namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\EndsWithNewline;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint
 * @group TokenConstraint\EndsWithNewline
 */
class EndsWithNewlineTest extends \Tests\TestCase
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
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\n\r")),
            true
        );

        #2
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\r")),
            true
        );

        #3
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "x\n")),
            true
        );

        #4
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "x\r\n")),
            true
        );

        #5
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "x\r")),
            true
        );

        #3
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "x\n ")),
            false
        );

        #4
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "x\r\n ")),
            false
        );

        #5
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "x\r ")),
            false
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers \PHP\Manipulator\TokenConstraint\EndsWithNewline::evaluate
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new EndsWithNewline();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}