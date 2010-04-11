<?php

namespace Tests\PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator\UnifyCasts;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group ContainerManipulator
 * @group ContainerManipulator\UnifyCasts
 */
class UnifyCastsTest extends \Tests\TestCase
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
     * @covers \PHP\Manipulator\ContainerManipulator\UnifyCasts::manipulate
     * @covers \PHP\Manipulator\ContainerManipulator\UnifyCasts::<protected>
     */
    public function testManipulate($container, $expectedContainer, $params, $strict)
    {
        $manipulator = new UnifyCasts();
        $manipulator->manipulate($container, $params);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}