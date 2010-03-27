<?php

require_once 'PHP/Formatter/ContainerManipulator/UnifyCasts.php';

class PHP_Formatter_ContainerManipulator_UnifyCastsTest extends PHPFormatterTestCase
{
    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();

        $data = array();
        $path = '/ContainerManipulator/UnifyCasts/';

        #0
        $data[] = array(
            $this->getTokenArrayFromFixtureFile($path . 'input1'),
            $this->getTokenArrayFromFixtureFile($path . 'output1'),
            array(),
            true,
            true
        );

        #1
        $data[] = array(
            $this->getTokenArrayFromFixtureFile($path . 'input2'),
            $this->getTokenArrayFromFixtureFile($path . 'output2'),
            array(
                T_INT_CAST => '(iNt)',
                T_BOOL_CAST => '(bOoL)',
                T_DOUBLE_CAST => '(dOuBlE)',
                T_OBJECT_CAST => '(oBjEcT)',
                T_STRING_CAST => '(sTrInG)',
                T_UNSET_CAST => '(uNsEt)',
                T_ARRAY_CAST => '(aRrAy)',
            ),
            true,
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers PHP_Formatter_ContainerManipulator_UnifyCasts::manipulate
     * @covers PHP_Formatter_ContainerManipulator_UnifyCasts::<protected>
     */
    public function testManipulate($container, $expectedContainer, $params, $changed, $strict)
    {
        $manipulator = new PHP_Formatter_ContainerManipulator_UnifyCasts();
        $this->assertSame($changed, $manipulator->manipulate($container, $params), 'Wrong return value');
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}