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
            array(),
            false
        );

        #1 include all whitespace AFTER it too
        $data[] = array(
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
            array(),
            false
        );

        #2 strip whitespace
        $data[] = array(
            $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
            array('stripWhitespaceFromEnd' => true),
            false
        );

        #3 strip whitespace
        $data[] = array(
            $this->getContainerFromFixture($path . 'input3'),
            $this->getContainerFromFixture($path . 'output3'),
            array('stripWhitespaceFromEnd' => true),
            false
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\ContainerManipulator\StripUnneededPhpCloseTag
     * @dataProvider manipulateProvider
     */
    public function testManipulate($container, $expectedContainer, $params, $strict)
    {
        $manipulator = new StripUnneededPhpCloseTag();
        $manipulator->manipulate($container, $params);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}