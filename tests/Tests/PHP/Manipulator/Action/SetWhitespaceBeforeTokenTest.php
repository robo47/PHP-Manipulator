<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\SetWhitespaceBeforeToken;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\SetWhitespaceBeforeToken
 */
class SetWhitespaceBeforeTokenTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\SetWhitespaceBeforeToken::init
     */
    public function testConstructorDefaults()
    {
        $action = new SetWhitespaceBeforeToken();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();

        $data = array();
        $path = '/Action/SetWhitespaceBeforeToken/';

        #0
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
            array(
                'tokens' => array($inputContainer[3]),
                'whitespace' => array(T_CONCAT_EQUAL => ' '),
            ),
            false
        );

        #1
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
            array(
                'tokens' => array($inputContainer[4]),
                'whitespace' => array(T_CONCAT_EQUAL => '  '),
            ),
            false
        );

        #2
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
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
     * @covers \PHP\Manipulator\Action\SetWhitespaceBeforeToken
     */
    public function testManipulate($container, $expectedContainer, $params, $strict)
    {
        $manipulator = new SetWhitespaceBeforeToken();
        $manipulator->run($container, $params);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}