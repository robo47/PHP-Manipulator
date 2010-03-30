<?php

/**
 * @group ContainerManipulator_RemoveTypehints
 */
class PHP_Formatter_ContainerManipulator_RemoveTypehintsTest extends PHPFormatterTestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();
        $path = '/ContainerManipulator/RemoveTypehints/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
            false
        );

        #1
        $data[] = array(
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
            false
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers PHP_Formatter_ContainerManipulator_RemoveTypehints
     */
    public function testManipulate($container, $expectedContainer, $strict)
    {
        $manipulator = new PHP_Formatter_ContainerManipulator_RemoveTypehints();
        $manipulator->manipulate($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}