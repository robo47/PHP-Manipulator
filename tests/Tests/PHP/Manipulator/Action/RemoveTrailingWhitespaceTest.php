<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveTrailingWhitespace;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\RemoveTrailingWhitespace
 */
class RemoveTrailingWhitespaceTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\RemoveTrailingWhitespace::init
     */
    public function testConstructorDefaults()
    {
        $action = new RemoveTrailingWhitespace();
        $this->assertTrue($action->getOption('removeEmptyLinesAtFileEnd'), 'Default Value for removeEmptyLinesAtFileEnd is wrong');
        $this->assertCount(1, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/RemoveTrailingWhitespace/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        #1
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
        );

        #2
        $data[] = array(
            array('removeEmptyLinesAtFileEnd' => false),
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\RemoveTrailingWhitespace::run
     * @dataProvider actionProvider
     * @param array $options
     * @param \PHP\Manipulator\TokenContainer $input
     * @param \PHP\Manipulator\TokenContainer $expectedTokens
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new RemoveTrailingWhitespace($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}