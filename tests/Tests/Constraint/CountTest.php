<?php

namespace Tests\Constraint;

use Tests\Constraint\Count;

// @todo test faile-message and stuff
class CountTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function arrayProvider()
    {
        $data = array();

        # 0
        $data[] = array(
            array(),
            0,
            true
        );

        # 1
        $data[] = array(
            array(),
            1,
            false
        );

        # 2
        $data[] = array(
            array(1,2,3,4,5),
            5,
            true
        );

        # 3
        $data[] = array(
            array(1,2,3,4,5),
            6,
            false
        );

        return $data;
    }

    /**
     * @dataProvider arrayProvider
     */
    public function testCountWithArray($other, $expected, $expectedEvaluationResult)
    {
        $count = new Count($expected);
        $this->assertSame($expectedEvaluationResult, $count->evaluate($other));
    }

    /**
     * @return array
     */
    public function countableProvider()
    {
        $data = array();

        # 0
        $data[] = array(
            new \ArrayObject(),
            0,
            true
        );

        # 1
        $data[] = array(
            new \ArrayObject(),
            1,
            false
        );

        # 2
        $data[] = array(
            new \ArrayObject(array(1,2,3,4,5)),
            5,
            true
        );

        # 2
        $data[] = array(
            new \ArrayObject(array(1,2,3,4,5)),
            6,
            false
        );

        return $data;
    }

    /**
     * @dataProvider countableProvider
     * @covers \Tests\Constraint\Count::evaluate
     * @covers \Tests\Constraint\Count::<protected>
     */
    public function testCountWithCountable($other, $expected, $expectedEvaluationResult)
    {
        $count = new Count($expected);
        $this->assertSame($expectedEvaluationResult, $count->evaluate($other));
    }

    /**
     * @return array
     */
    public function iteratorProvider()
    {
        $data = array();

        # 0
        $data[] = array(
            new \ArrayIterator(array()),
            0,
            true
        );

        # 1
        $data[] = array(
            new \ArrayIterator(array()),
            1,
            false
        );

        # 2
        $data[] = array(
            new \ArrayIterator(array(1,2,3,4,5)),
            5,
            true
        );

        # 3
        $data[] = array(
            new \ArrayIterator(array(1,2,3,4,5)),
            6,
            false
        );

        return $data;
    }

    /**
     * @dataProvider iteratorProvider
     * @covers \Tests\Constraint\Count::evaluate
     * @covers \Tests\Constraint\Count::<protected>
     */
    public function testCountWithIterator($other, $expected, $expectedEvaluationResult)
    {
        $count = new Count($expected);
        $this->assertSame($expectedEvaluationResult, $count->evaluate($other));
    }

    /**
     * @covers \Tests\Constraint\Count::toString
     */
    public function testToString()
    {
        $count = new Count(0);
        $this->assertEquals('Count matches ', $count->toString());
    }
}