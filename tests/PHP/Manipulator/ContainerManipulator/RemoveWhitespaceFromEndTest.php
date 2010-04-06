<?php

namespace Tests\PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator\RemoveWhitespaceFromEnd;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group ContainerManipulator_RemoveWhitespaceFromEnd
 */
class RemoveWhitespaceFromEndTest extends \Tests\TestCase
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
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
            false
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers PHP\Manipulator\ContainerManipulator\RemoveWhitespaceFromEnd
     */
    public function testManipulate($container, $expectedContainer, $strict)
    {
        $manipulator = new RemoveWhitespaceFromEnd();
        $manipulator->manipulate($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}