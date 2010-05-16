<?php

namespace Tests\Constraint;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\Token;
use Tests\Constraint\TokensMatch;

function foo(Token $blub) {

}

class ValidTokenMatchingClosureTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Tests\Constraint\TokensMatch::__construct
     */
    public function testConstruct()
    {
        $token = new ValidTokenMatchingClosure();
    }

    /**
     * @return array
     */
    public function closuresProvider()
    {
        $data = array();

        #0
        $data[] = array(
            function(Token $token) { },
            true
        );
            
        #1
        $data[] = array(
            function(Result $token) { },
            false
        );

        #2
        $data[] = array(
            function(Token $token, Result $result) { },
            false
        );

        #3
        $data[] = array(
            function() { },
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
        $this->assertSame($expectedResult, $valid->evaluate($closure));
    }

    /**
     * @covers \Tests\Constraint\ValidTokenMatchingClosure::failureDescription
     */
    public function testFailAndFailureDescriptionWithWrongTypehint()
    {
        $other = function(Result $token) { };
        $valid = new ValidTokenMatchingClosure();
        $valid->evaluate($other);

        $message = 'Closures Token-Parameter has wrong Typehint';

        try {
            $valid->fail($other, '');
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
        $other = function(Token $token, Result $result) { };
        $valid = new ValidTokenMatchingClosure();
        $valid->evaluate($other);

        $message = 'Closure does not have 1 required parameter';

        try {
            $valid->fail($other, '');
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
        $valid->evaluate($other);

        $message = 'Variable is no Closure';

        try {
            $valid->fail($other, '');
            $this->fail('no exception thrown');
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            $this->assertEquals($message, $e->getMessage());
        }
    }
}