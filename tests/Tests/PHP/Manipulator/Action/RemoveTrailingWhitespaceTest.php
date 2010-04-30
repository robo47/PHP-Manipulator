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
        $removeEmptyLinesAtFileEnd = $action->getOption('removeEmptyLinesAtFileEnd');
        $this->assertTrue($removeEmptyLinesAtFileEnd, 'Default Value for removeEmptyLinesAtFileEnd is wrong');
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

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\RemoveTrailingWhitespace::run
     * @dataProvider actionProvider
     * @param array $options
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new RemoveTrailingWhitespace($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}