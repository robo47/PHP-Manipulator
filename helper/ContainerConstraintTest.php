<?php

/**
 * @group __classname__
 */
class PHP_Formatter___classname__Test extends PHPFormatterTestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();
        $path = '/__path__/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0'),
            true,
        );

        return $data;
    }

    /**
     * @covers PHP_Formatter___classname__::evaluate
     * @dataProvider evaluateProvider
     */
    public function testContainerConstraint($input, $expectedResult)
    {
        $this->markTestSkipped('not implemented yet');
        $constraint = new PHP_Formatter___classname__();
        $this->assertSame($expectedResult, $constraint->evaluate($input));
    }
}