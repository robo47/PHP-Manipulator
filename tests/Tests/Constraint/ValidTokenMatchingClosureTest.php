<?php

namespace Tests\Constraint;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\Token;
use Tests\Constraint\TokensMatch;

function foo(Token $blub)
{

}

/**
 * @group ValidTokenMatchingClosure
 */
class ValidTokenMatchingClosureTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function closuresProvider()
    {
        $data = array();

        #0
        $data[] = array(
            function(Token $token) {
            },
            true
        );

        #1
        $data[] = array(
            function(Result $token) {
            },
            false
        );

        #2
        $data[] = array(
            function(Token $token, Result $result) {
            },
            false
        );

        #3
        $data[] = array(
            function() {
            },
            false
        );

        #4
        $data[] = array(
            '',
            false
        );

        #5
        $data[] = array(
            'foo',
            false
        );

        return $data;
    }

    /**
     * @dataProvider closuresProvider
     * @covers \Tests\Constraint\ValidTokenMatchingClosure::evaluate
     * @covers \Tests\Constraint\ValidTokenMatchingClosure::<protected>
     */
    public function testTokensMatch($closure, $expectedResult)
    {
        $valid = new ValidTokenMatchingClosure();
        $this->assertSame($expectedResult, $valid->evaluate($closure, '', true));
    }

    /**
     * @covers \Tests\Constraint\ValidTokenMatchingClosure::failureDescription
     */
    public function testFailAndFailureDescriptionWithWrongTypehint()
    {
        $other = function(Result $token) {
        };
        $valid = new ValidTokenMatchingClosure();
        $message = 'Failed asserting that Closures Token-Parameter has wrong Typehint.';

        try {
            $valid->evaluate($other, '', false);
            $this->fail('no exception thrown');
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            $this->assertEquals($message, $e->getMessage());
        }
    }

    /**
     * @covers \Tests\Constraint\ValidTokenMatchingClosure::failureDescription
     */
    public function testFailAndFailureDescriptionWithWrongParameterCount()
    {
        $other = function(Token $token, Result $result) {
        };
        $valid = new ValidTokenMatchingClosure();


        $message = 'Failed asserting that Closure does not have 1 required parameter.';

        try {
            $valid->evaluate($other);
            $this->fail('no exception thrown');
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            $this->assertEquals($message, $e->getMessage());
        }
    }

    /**
     * @covers \Tests\Constraint\ValidTokenMatchingClosure::failureDescription
     */
    public function testFailAndFailureDescriptionWithNoClosure()
    {
        $other = '';
        $valid = new ValidTokenMatchingClosure();

        $message = 'Failed asserting that Variable must be a Closure.';

        try {
            $valid->evaluate($other);
            $this->fail('no exception thrown');
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            $this->assertEquals($message, $e->getMessage());
        }
    }

    /**
     * @covers \Tests\Constraint\ValidTokenMatchingClosure::toString
     */
    public function testToString()
    {
        $valid = new ValidTokenMatchingClosure();
        $this->assertEquals('Is a valid Token Matching Closure ',
            $valid->toString());
    }

}
