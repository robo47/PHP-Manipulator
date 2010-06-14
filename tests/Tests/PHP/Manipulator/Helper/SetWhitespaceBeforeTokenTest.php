<?php

namespace Tests\PHP\Manipulator\Helper;

use PHP\Manipulator\Helper\SetWhitespaceBeforeToken;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Helper
 * @group Helper\SetWhitespaceBeforeToken
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
        $path = '/Helper/SetWhitespaceBeforeToken/';

        #0
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
            array($inputContainer[3]),
            array(T_CONCAT_EQUAL => ' '),
            false
        );

        #1
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
            array($inputContainer[4]),
            array(T_CONCAT_EQUAL => '  '),
            false
        );

        #2
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
            array($inputContainer[4]),
            array(T_CONCAT_EQUAL => ''),
            false
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers \PHP\Manipulator\Helper\SetWhitespaceBeforeToken
     */
    public function testManipulate($container, $expectedContainer, $tokens, $whitespace, $strict)
    {
        $manipulator = new SetWhitespaceBeforeToken();
        $manipulator->run($container, $tokens, $whitespace);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}