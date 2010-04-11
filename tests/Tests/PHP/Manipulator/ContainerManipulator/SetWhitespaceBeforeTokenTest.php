<?php

namespace Tests\PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator\SetWhitespaceBeforeToken;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group ContainerManipulator
 * @group ContainerManipulator\SetWhitespaceBeforeToken
 */
class SetWhitespaceBeforeTokenTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();

        $data = array();
        $path = '/ContainerManipulator/SetWhitespaceBeforeToken/';

        #0
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
            array(
                'tokens' => array($inputContainer[3]),
                'whitespace' => array(T_CONCAT_EQUAL => ' '),
            ),
            false
        );

        #1
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
            array(
                'tokens' => array($inputContainer[4]),
                'whitespace' => array(T_CONCAT_EQUAL => '  '),
            ),
            false
        );

        #2
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
            array(
                'tokens' => array($inputContainer[4]),
                'whitespace' => array(T_CONCAT_EQUAL => ''),
            ),
            false
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers \PHP\Manipulator\ContainerManipulator\SetWhitespaceBeforeToken
     */
    public function testManipulate($container, $expectedContainer, $params, $strict)
    {
        $manipulator = new SetWhitespaceBeforeToken();
        $manipulator->manipulate($container, $params);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}