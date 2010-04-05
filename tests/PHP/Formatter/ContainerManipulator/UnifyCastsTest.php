<?php

/**
 * @group ContainerManipulator_UnifyCasts
 */
class PHP_Formatter_ContainerManipulator_UnifyCastsTest extends TestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();
        $path = '/ContainerManipulator/UnifyCasts/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
            array(
                T_INT_CAST => '(iNt)',
                T_BOOL_CAST => '(bOoL)',
                T_DOUBLE_CAST => '(dOuBlE)',
                T_OBJECT_CAST => '(oBjEcT)',
                T_STRING_CAST => '(sTrInG)',
                T_UNSET_CAST => '(uNsEt)',
                T_ARRAY_CAST => '(aRrAy)',
            ),
            true
        );

        #1
        $data[] = array(
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
            array(),
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers PHP_Formatter_ContainerManipulator_UnifyCasts::manipulate
     * @covers PHP_Formatter_ContainerManipulator_UnifyCasts::<protected>
     */
    public function testManipulate($container, $expectedContainer, $params, $strict)
    {
        $manipulator = new PHP_Formatter_ContainerManipulator_UnifyCasts();
        $manipulator->manipulate($container, $params);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}