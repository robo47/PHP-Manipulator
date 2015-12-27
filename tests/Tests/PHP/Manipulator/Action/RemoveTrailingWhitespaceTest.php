<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveTrailingWhitespace;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\RemoveTrailingWhitespace
 */
class RemoveTrailingWhitespaceTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new RemoveTrailingWhitespace();
        $this->assertTrue(
            $action->getOption(RemoveTrailingWhitespace::OPTION_REMOVE_EMPTY_LINES_AT_FILE_END),
            'Default Value for removeEmptyLinesAtFileEnd is wrong'
        );
        $this->assertCount(1, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = [];
        $path = '/Action/RemoveTrailingWhitespace/';

        #0
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input0.php'),
            $this->getContainerFromFixture($path.'output0.php'),
        ];

        #1
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input1.php'),
            $this->getContainerFromFixture($path.'output1.php'),
        ];

        #2
        $data[] = [
            [RemoveTrailingWhitespace::OPTION_REMOVE_EMPTY_LINES_AT_FILE_END => false],
            $this->getContainerFromFixture($path.'input2.php'),
            $this->getContainerFromFixture($path.'output2.php'),
        ];

        return $data;
    }

    /**
     * @dataProvider actionProvider
     *
     * @param array          $options
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     */
    public function testAction(array $options, TokenContainer $input, TokenContainer $expectedTokens)
    {
        $action = new RemoveTrailingWhitespace($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
