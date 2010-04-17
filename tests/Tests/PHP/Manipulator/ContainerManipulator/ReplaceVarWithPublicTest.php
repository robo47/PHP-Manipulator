<?php

namespace Tests\PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator\ReplaceVarWithPublic;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group ContainerManipulator_ReplaceVarWithPublic
 */
class ReplaceVarWithPublicTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();
        $path = '/ContainerManipulator/ReplaceVarWithPublic/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
            false
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\ContainerManipulator\ReplaceVarWithPublic
     *      * @dataProvider manipulateProvider
     */
    public function testManipulate($container, $expectedContainer, $strict)
    {
        $manipulator = new ReplaceVarWithPublic();
        $manipulator->manipulate($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}