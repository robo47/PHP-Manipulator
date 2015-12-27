<?php

namespace Tests\Constraint;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenFinder\Result;
use PHPUnit_Framework_Exception;
use PHPUnit_Framework_ExpectationFailedException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Tests\Constraint\ResultsMatch
 */
class ResultsMatchTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $result = new Result();
        $this->assertInstanceOf(ResultsMatch::class, new ResultsMatch($result));
    }

    /**
     * @return array
     */
    public function resultsProvider()
    {
        $data = [];

        $t1 = Token::createFromValue('Baa');
        $t2 = Token::createFromValue('Foo');
        $t3 = Token::createFromValue('Blub');

        # 0
        $data[] = [
            Result::factory([$t1, $t2, $t3]),
            Result::factory([$t1, $t2, $t3]),
            true,
        ];

        # 1
        $data[] = [
            Result::factory([]),
            Result::factory([]),
            true,
        ];

        # 2
        $data[] = [
            Result::factory([$t1, $t2]),
            Result::factory([$t1, $t2, $t3]),
            false,
        ];

        # 3
        $data[] = [
            Result::factory([$t1, $t2, $t3, $t2]),
            Result::factory([$t1, $t2, $t3]),
            false,
        ];

        # 4
        $data[] = [
            Result::factory([$t1, $t2, $t2]),
            Result::factory([$t1, $t2, $t3]),
            false,
        ];

        # 5 Check that really === is used for comparision
        $data[] = [
            Result::factory([clone $t1, clone $t2, clone $t3]),
            Result::factory([$t1, $t2, $t3]),
            false,
        ];

        # 6
        $data[] = [
            Result::factory([$t3, $t2, $t1]),
            Result::factory([$t1, $t2, $t3]),
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider resultsProvider
     *
     * @param Result $other
     * @param Result $expected
     * @param bool   $expectedEvaluationResult
     */
    public function testResultsMatch(Result $other, Result $expected, $expectedEvaluationResult)
    {
        $resultsMatch = new ResultsMatch($expected);
        $this->assertSame($expectedEvaluationResult, $resultsMatch->evaluate($other, '', true));
    }

    public function testToString()
    {
        $resultsMatch = new ResultsMatch(new Result());
        $this->assertSame('Result matches ', $resultsMatch->toString());
    }

    public function testEvaludateThrowsExceptionIfOtherIsNoResult()
    {
        $this->setExpectedException(
            PHPUnit_Framework_Exception::class,
            'must be a PHP\\Manipulator\\Tokenfinder\\Result'
        );
        $resultsMatch = new ResultsMatch(new Result());
        $resultsMatch->evaluate('foo');
    }

    public function testFailAndFailureDescription()
    {
        $this->setExpectedException(
            PHPUnit_Framework_ExpectationFailedException::class,
            'asserting that Results do not match'
        );
        $expected     = new Result();
        $other        = Result::factory([Token::createFromValue('Foo')]);
        $resultsMatch = new ResultsMatch($expected);
        $resultsMatch->evaluate($other, '');
    }
}
