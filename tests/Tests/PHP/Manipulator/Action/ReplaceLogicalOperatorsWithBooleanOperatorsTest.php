<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ReplaceLogicalOperatorsWithBooleanOperators;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\ReplaceLogicalOperatorsWithBooleanOperators
 */
class ReplaceLogicalOperatorsWithBooleanOperatorsTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new ReplaceLogicalOperatorsWithBooleanOperators();
        $this->assertTrue(
            $action->getOption(ReplaceLogicalOperatorsWithBooleanOperators::OPTION_REPLACE_AND),
            'Default value for replaceAnd is wrong'
        );
        $this->assertTrue(
            $action->getOption(ReplaceLogicalOperatorsWithBooleanOperators::OPTION_REPLACE_OR),
            'Default value for replaceOr is wrong'
        );
        $this->assertCount(2, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = [];
        $path = '/Action/ReplaceLogicalOperatorsWithBooleanOperators/';

        #0
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input0.php'),
            $this->getContainerFromFixture($path.'output0.php'),
        ];

        #1
        $data[] = [
            [ReplaceLogicalOperatorsWithBooleanOperators::OPTION_REPLACE_AND => false],
            $this->getContainerFromFixture($path.'input1.php'),
            $this->getContainerFromFixture($path.'output1.php'),
        ];

        #2
        $data[] = [
            [ReplaceLogicalOperatorsWithBooleanOperators::OPTION_REPLACE_OR => false],
            $this->getContainerFromFixture($path.'input2.php'),
            $this->getContainerFromFixture($path.'output2.php'),
        ];

        #3
        $data[] = [
            [ReplaceLogicalOperatorsWithBooleanOperators::OPTION_REPLACE_OR  => false,
             ReplaceLogicalOperatorsWithBooleanOperators::OPTION_REPLACE_AND => false,
            ],
            $this->getContainerFromFixture($path.'input3.php'),
            $this->getContainerFromFixture($path.'output3.php'),
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
        $action = new ReplaceLogicalOperatorsWithBooleanOperators($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
