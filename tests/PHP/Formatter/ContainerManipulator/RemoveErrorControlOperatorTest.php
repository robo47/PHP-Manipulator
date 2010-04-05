<?php

/**
 * @group ContainerManipulator_RemoveErrorControlOperator
 */
class PHP_Formatter_ContainerManipulator_RemoveErrorControlOperatorTest extends TestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();
        $path = '/ContainerManipulator/RemoveErrorControlOperator/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
            false
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers PHP_Formatter_ContainerManipulator_RemoveErrorControlOperator
     */
    public function testManipulate($container, $expectedContainer, $strict)
    {
        $manipulator = new PHP_Formatter_ContainerManipulator_RemoveErrorControlOperator();
        $manipulator->manipulate($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}