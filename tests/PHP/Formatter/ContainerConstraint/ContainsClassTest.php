<?php

class PHP_Formatter_ContainerConstraint_ContainsClassTest extends PHPFormatterTestCase
{
    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();
        $path = '/ContainerConstraint/ContainsClass/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0'),
            true,
        );

        #1
        $data[] = array(
            $this->getContainerFromFixture($path . 'input1'),
            false,
        );

        return $data;
    }


    /**
     * @covers PHP_Formatter_ContainerConstraint_ContainsClass::evaluate
     * @dataProvider evaluateProvider
     */
    public function testContainerConstraint($input, $expectedResult)
    {
        $constraint = new PHP_Formatter_ContainerConstraint_ContainsClass();
        $this->assertSame($expectedResult, $constraint->evaluate($input));
    }
}