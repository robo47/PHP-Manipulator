<?php

namespace Tests\Constraint;

use Tests\Constraint\Count;
use PHP\Manipulator\TokenContainer\Iterator;
use PHP\Manipulator\TokenContainer;

class CountTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Tests\Constraint\Count::__construct
     */
    public function testConstruct()
    {
        $count = new Count(5);
    }

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
            array(1, 2, 3, 4, 5),
            5,
            true
        );

        # 3
        $data[] = array(
            array(1, 2, 3, 4, 5),
            6,
            false
        );

        return $data;
    }

    /**
     * @dataProvider arrayProvider
     * @covers \Tests\Constraint\Count::evaluate
     * @covers \Tests\Constraint\Count::<protected>
     */
    public function testCountWithArray($other, $expected, $expectedEvaluationResult)
    {
        $count = new Count($expected);
        $this->assertSame($expectedEvaluationResult, $count->evaluate($other));
        try {
            $count->fail($other, '');
            $this->fail('No exception thrown');
        } catch(\PHPUnit_Framework_ExpectationFailedException $e) {
            $this->assertEquals('Count of ' . count($other) . ' does not match expected count of ' . $expected, $e->getMessage());
        }
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
            new \ArrayObject(array(1, 2, 3, 4, 5)),
            5,
            true
        );

        # 2
        $data[] = array(
            new \ArrayObject(array(1, 2, 3, 4, 5)),
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
        try {
            $count->fail($other, '');
            $this->fail('No exception thrown');
        } catch(\PHPUnit_Framework_ExpectationFailedException $e) {
            $this->assertEquals('Count of ' . count($other) . ' does not match expected count of ' . $expected, $e->getMessage());
        }
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

        #1
        $data[] = array(
            new \ArrayIterator(array()),
            1,
            false
        );

        #2
        $data[] = array(
            new \ArrayIterator(array(1, 2, 3, 4, 5)),
            5,
            true
        );

        #3
        $data[] = array(
            new \ArrayIterator(array(1, 2, 3, 4, 5)),
            6,
            false
        );

        #4
        $data[] = array(
            new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(TESTS_PATH . '/Baa/Autoloader/')),
            4,
            true
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
        try {
            $count->fail($other, '');
            $this->fail('No exception thrown');
        } catch(\PHPUnit_Framework_ExpectationFailedException $e) {
            $this->assertEquals('Count of ' . iterator_count($other) . ' does not match expected count of ' . $expected, $e->getMessage());
        }
    }

    /**
     * @covers \Tests\Constraint\Count::toString
     */
    public function testToString()
    {
        $count = new Count(0);
        $this->assertEquals('Count matches ', $count->toString());
    }

    /**
     * @covers \Tests\Constraint\Count::__construct
     */
    public function testConstructorThrowsExceptionIfExpectedIsNotInteger()
    {
        try {
            $count = new Count('0');
            $this->fail('Expected exception not thrown');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Argument #1 of Tests\Constraint\Count::__construct() is no integer', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \Tests\Constraint\Count::evaluate
     * @covers \Tests\Constraint\Count::<protected>
     */
    public function testEvaludateThrowsExceptionIfOtherIsNotInteger()
    {
        $count = new Count(0);
        try {
            $count->evaluate("foo");
            $this->fail('Expected exception not thrown');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Argument #1 of Tests\Constraint\Count::evaluate() is no countable type', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \Tests\Constraint\Count::failureDescription
     */
    public function testFailAndFailureDescription()
    {
        $expected = 0;
        $other = array(1, 2, 3);

        $count = new Count($expected);
        $count->evaluate($other);

        try {
            $count->fail($other, '');
            $this->fail('no exception thrown');
            //$this->assertEquals(, );
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            $this->assertEquals('Count of 3 does not match expected count of 0', $e->getMessage());
        }
    }
}