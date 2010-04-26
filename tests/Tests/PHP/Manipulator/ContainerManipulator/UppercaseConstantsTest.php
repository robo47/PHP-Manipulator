<?php

namespace Tests\PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator\UppercaseConstants;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group ContainerManipulator\UppercaseConstants
 */
class UppercaseConstantsTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();
        $path = '/ContainerManipulator/UppercaseConstants/';

        #0 Simple class-Constant and accessing it
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
            false
        );

        #1 Test it does not uppercase method-calls
        $data[] = array(
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
            false
        );

        #2 Normal constant
        $data[] = array(
            $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
            false
        );

        #3 function-parameter
        $data[] = array(
            $this->getContainerFromFixture($path . 'input3'),
            $this->getContainerFromFixture($path . 'output3'),
            false
        );

        #4 method-parameter
        $data[] = array(
            $this->getContainerFromFixture($path . 'input4'),
            $this->getContainerFromFixture($path . 'output4'),
            false
        );

        #5 namespaces should not be uppercased (using namespace via curly braces)
        $data[] = array(
            $this->getContainerFromFixture($path . 'input5'),
            $this->getContainerFromFixture($path . 'output5'),
            false
        );

        #6 namespaces should not be uppercased
        $data[] = array(
            $this->getContainerFromFixture($path . 'input6'),
            $this->getContainerFromFixture($path . 'output6'),
            false
        );

        #7 use inside namespace (using namespace via curly braces)
        $data[] = array(
            $this->getContainerFromFixture($path . 'input7'),
            $this->getContainerFromFixture($path . 'output7'),
            false
        );

        #8 use inside namespace
        $data[] = array(
            $this->getContainerFromFixture($path . 'input8'),
            $this->getContainerFromFixture($path . 'output8'),
            false
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\ContainerManipulator\UppercaseConstants
     * @dataProvider manipulateProvider
     */
    public function testManipulate($container, $expectedContainer, $strict)
    {
        $manipulator = new UppercaseConstants();
        $manipulator->manipulate($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}