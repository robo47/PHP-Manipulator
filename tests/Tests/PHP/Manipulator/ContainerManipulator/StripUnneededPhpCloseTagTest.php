<?php

namespace Tests\PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator\StripUnneededPhpCloseTag;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group ContainerManipulator\StripUnneededPhpCloseTag
 */
class StripUnneededPhpCloseTagTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();
        $path = '/ContainerManipulator/StripUnneededPhpCloseTag/';

        #0 Strip the ? > including whitespace before it
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
            false
        );

        #1 include all whitespace AFTER it too
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
            false
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\ContainerManipulator\StripUnneededPhpCloseTag
     * @dataProvider manipulateProvider
     */
    public function testManipulate($container, $expectedContainer, $strict)
    {
        $manipulator = new StripUnneededPhpCloseTag();
        $manipulator->manipulate($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}