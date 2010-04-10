<?php

namespace Tests\Constraint;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\Token;
use Tests\Constraint\ResultsMatch;

// @todo test faile-message and stuff
class ResultsMatchTest extends \PHPUnit_Framework_TestCase
{
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
        $count = new ResultsMatch($expected);
        $this->assertSame($expectedEvaluationResult, $count->evaluate($other));
    }

    /**
     * @covers \Tests\Constraint\ResultsMatch::toString
     */
    public function testToString()
    {
        $count = new ResultsMatch(new Result());
        $this->assertEquals('Result matches ', $count->toString());
    }
}