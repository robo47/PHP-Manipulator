<?php

namespace Tests\Constraint;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\Token;
use Tests\Constraint\ResultsMatch;
use Tests\Util;

class ResultsMatchTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Tests\Constraint\ResultsMatch::__construct
     */
    public function testConstruct()
    {
        $resultsMatch = new ResultsMatch(new Result());
    }

    /**
     * @return array
     */
    public function resultsProvider()
    {
        $data = array();

        $t1 = new Token('Baa');
        $t2 = new Token('Foo');
        $t3 = new Token('Blub');

        # 0
        $data[] = array(
            Result::factory(array($t1, $t2, $t3)),
            Result::factory(array($t1, $t2, $t3)),
            true
        );

        # 1
        $data[] = array(
            Result::factory(array()),
            Result::factory(array()),
            true
        );

        # 2
        $data[] = array(
            Result::factory(array($t1, $t2)),
            Result::factory(array($t1, $t2, $t3)),
            false
        );

        # 3
        $data[] = array(
            Result::factory(array($t1, $t2, $t3, $t2)),
            Result::factory(array($t1, $t2, $t3)),
            false
        );

        # 4
        $data[] = array(
            Result::factory(array($t1, $t2, $t2)),
            Result::factory(array($t1, $t2, $t3)),
            false
        );

        # 5 Check that really === is used for comparision
        $data[] = array(
            Result::factory(array(clone $t1, clone $t2, clone $t3)),
            Result::factory(array($t1, $t2, $t3)),
            false
        );

        # 6
        $data[] = array(
            Result::factory(array($t3, $t2, $t1)),
            Result::factory(array($t1, $t2, $t3)),
            false
        );

        return $data;
    }

    /**
     * @dataProvider resultsProvider
     */
    public function testResultsMatch($other, $expected, $expectedEvaluationResult)
    {
        $resultsMatch = new ResultsMatch($expected);
        $this->assertSame($expectedEvaluationResult, $resultsMatch->evaluate($other));
    }

    /**
     * @covers \Tests\Constraint\ResultsMatch::toString
     */
    public function testToString()
    {
        $resultsMatch = new ResultsMatch(new Result());
        $this->assertEquals('Result matches ', $resultsMatch->toString());
    }

    /**
     * @covers \Tests\Constraint\ResultsMatch::__construct
     */
    public function testConstructorThrowsExceptionIfExpectedIsNoResult()
    {
        try {
            $count = new ResultsMatch('0');
            $this->fail('Expected exception not thrown');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Argument #1 of Tests\Constraint\ResultsMatch::__construct() is no \PHP\Manipulator\Tokenfinder\Result', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \Tests\Constraint\ResultsMatch::evaluate
     * @covers \Tests\Constraint\ResultsMatch::<protected>
     */
    public function testEvaludateThrowsExceptionIfOtherIsNoResult()
    {
        $resultsMatch = new ResultsMatch(new Result());
        try {
            $resultsMatch->evaluate("foo");
            $this->fail('Expected exception not thrown');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Argument #1 of Tests\Constraint\ResultsMatch::evaluate() is no \PHP\Manipulator\Tokenfinder\Result', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \Tests\Constraint\ResultsMatch::failureDescription
     */
    public function testFailAndFailureDescription()
    {
        $expected = new Result();
        $other = Result::factory(array(new Token('Foo')));
        $resultsMatch = new ResultsMatch($expected);
        $resultsMatch->evaluate($other);

        $message =
        'Results do not match: ' . PHP_EOL .
        'Cause: length' . PHP_EOL .
        Util::compareResults($expected, $other);

        try {
            $resultsMatch->fail($other, '');
            $this->fail('no exception thrown');
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            $this->assertEquals($message, $e->getMessage());
        }
    }
}