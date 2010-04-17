<?php

namespace Tests\PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator\__classname__;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group ContainerManipulator\__classname__
 */
class __classname__Test extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();
        $path = '/__path__/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
            false
        );

        return $data;
    }

    /**
     * @covers __completeclassname__
     *      * @dataProvider manipulateProvider
     */
    public function testManipulate($container, $expectedContainer, $strict)
    {
        $this->markTestSkipped('not implemented yet');
        $manipulator = new __classname__();
        $manipulator->manipulate($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}