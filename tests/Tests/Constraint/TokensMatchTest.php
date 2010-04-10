<?php

namespace Tests\Constraint;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\Token;
use Tests\Constraint\TokensMatch;

// @todo test faile-message and stuff
class TokensMatchTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function tokensProvider()
    {
        $data = array();

        # 0
        $data[] = array(
            new Token('foo', T_WHITESPACE, 5),
            new Token('foo', T_WHITESPACE, 5),
            true,
            true
        );

        # 1
        $data[] = array(
            new Token('foo', T_WHITESPACE, 5),
            new Token('foo', T_WHITESPACE, 6),
            false,
            true
        );

        # 2
        $data[] = array(
            new Token('foo', T_WHITESPACE, 5),
            new Token('foo', T_WHITESPACE, 6),
            true,
            false
        );

        # 3
        $data[] = array(
            new Token('foo', null, 5),
            new Token('foo', T_WHITESPACE, 5),
            false,
            false
        );

        # 4
        $data[] = array(
            new Token('foo', null, 5),
            new Token('foo', T_WHITESPACE, 5),
            false,
            true
        );

        return $data;
    }

    /**
     * @dataProvider tokensProvider
     * @covers \Tests\Constraint\TokensMatch::evaluate
     * @covers \Tests\Constraint\TokensMatch::<protected>
     */
    public function testTokensMatch($other, $expected, $expectedEvaluationResult, $strict)
    {
        $count = new TokensMatch($expected, $strict);
        $this->assertSame($expectedEvaluationResult, $count->evaluate($other));
    }

    /**
     * @covers \Tests\Constraint\TokensMatch::toString
     */
    public function testToString()
    {
        $count = new TokensMatch(new Token('Foo'), true);
        $this->assertEquals('Token matches another Token', $count->toString());
    }
}