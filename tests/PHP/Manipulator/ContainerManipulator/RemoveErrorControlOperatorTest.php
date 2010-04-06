<?php

namespace Tests\PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator\RemoveErrorControlOperator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group ContainerManipulator_RemoveErrorControlOperator
 */
class RemoveErrorControlOperatorTest extends \Tests\TestCase
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
     * @covers PHP\Manipulator\ContainerManipulator\RemoveErrorControlOperator
     */
    public function testManipulate($container, $expectedContainer, $strict)
    {
        $manipulator = new RemoveErrorControlOperator();
        $manipulator->manipulate($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}