<?php

namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\IsSemicolon;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint\IsSemicolon
 */
class IsSemicolonTest
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
            Token::factory(array(null, ';')),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_WHITESPACE, ';')),
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
     * @covers \PHP\Manipulator\TokenConstraint\IsSemicolon
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new IsSemicolon();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}