<?php

namespace Tests\Constraint;

use Closure;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenFinder\Result;
use PHPUnit_Framework_ExpectationFailedException;
use PHPUnit_Framework_TestCase;

function foo(Token $blub)
{
}

/**
 * @covers Tests\Constraint\ValidTokenMatchingClosure
 */
class ValidTokenMatchingClosureTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function closuresProvider()
    {
        $data = [];

        #0
        $data[] = [
            function (Token $token) {
            },
            true,
        ];

        #1
        $data[] = [
            function (Result $token) {
            },
            false,
        ];

        #2
        $data[] = [
            function (Token $token, Result $result) {
            },
            false,
        ];

        #3
        $data[] = [
            function () {
            },
            false,
        ];

        #4
        $data[] = [
            '',
            false,
        ];

        #5
        $data[] = [
            'foo',
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider closuresProvider
     *
     * @param Closure|string $closure
     * @param bool           $expectedResult
     */
    public function testTokensMatch($closure, $expectedResult)
    {
        $valid = new ValidTokenMatchingClosure();
        $this->assertSame($expectedResult, $valid->evaluate($closure, '', true));
    }

    public function testFailAndFailureDescriptionWithWrongTypehint()
    {
        $other = function (Result $token) {
        };
        $valid = new ValidTokenMatchingClosure();
        $this->setExpectedException(
            PHPUnit_Framework_ExpectationFailedException::class,
            'Token-Parameter has wrong Typehint'
        );
        $valid->evaluate($other, '', false);
    }

    public function testFailAndFailureDescriptionWithWrongParameterCount()
    {
        $other = function (Token $token, Result $result) {
        };
        $valid = new ValidTokenMatchingClosure();

        $this->setExpectedException(
            PHPUnit_Framework_ExpectationFailedException::class,
            'Closure does not have 1 required parameter'
        );
        $valid->evaluate($other);
    }

    public function testFailAndFailureDescriptionWithNoClosure()
    {
        $valid = new ValidTokenMatchingClosure();

        $this->setExpectedException(
            PHPUnit_Framework_ExpectationFailedException::class,
            'Variable must be a Closure'
        );
        $valid->evaluate('');
    }

    public function testToString()
    {
        $valid = new ValidTokenMatchingClosure();
        $this->assertSame('Is a valid Token Matching Closure ', $valid->toString());
    }
}
