<?php

/**
 * @group ContainerManipulator_RemoveWhitespaceFromEnd
 */
class PHP_Formatter_ContainerManipulator_RemoveWhitespaceFromEndTest extends PHPFormatterTestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();
        $path = '/ContainerManipulator/RemoveWhitespaceFromEnd/';

        #0
        $data[] = array(
            $this->getTokenArrayFromFixtureFile($path . 'input0'),
            $this->getTokenArrayFromFixtureFile($path . 'output0'),
            false
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers PHP_Formatter_ContainerManipulator_RemoveWhitespaceFromEnd
     */
    public function testManipulate($container, $expectedContainer, $strict)
    {
        $manipulator = new PHP_Formatter_ContainerManipulator_RemoveWhitespaceFromEnd();
        $manipulator->manipulate($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}