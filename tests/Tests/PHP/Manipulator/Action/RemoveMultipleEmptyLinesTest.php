<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveMultipleEmptyLines;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\RemoveMultipleEmptyLines
 */
class RemoveMultipleEmptyLinesTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new RemoveMultipleEmptyLines();
        $this->assertSame(
            2,
            $action->getOption(RemoveMultipleEmptyLines::OPTION_MAX_EMPTY_LINES),
            'Default value for maxEmptyLines is wrong'
        );
        $this->assertCount(1, $action->getOptions());
    }

    /**
     * @todo   Write Tests with maxEmptyLines
     *
     * @return array
     */
    public function actionProvider()
    {
        $data = [];
        $path = '/Action/RemoveMultipleEmptyLines/';

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
            [],
            $this->getContainerFromFixture($path.'input2.php'),
            $this->getContainerFromFixture($path.'output2.php'),
        ];

        return $data;
    }

    /**
     * @param array          $options
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     *
     * @dataProvider actionProvider
     */
    public function testAction(array $options, TokenContainer $input, TokenContainer $expectedTokens)
    {
        $action = new RemoveMultipleEmptyLines($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
