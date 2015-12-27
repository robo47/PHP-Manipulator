<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ReplaceBooleanOperatorsWithLogicalOperators;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\ReplaceBooleanOperatorsWithLogicalOperators
 */
class ReplaceBooleanOperatorsWithLogicalOperatorsTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new ReplaceBooleanOperatorsWithLogicalOperators();
        $this->assertFalse(
            $action->getOption(ReplaceBooleanOperatorsWithLogicalOperators::OPTION_UPPERCASE),
            'Default value for uppercase is wrong'
        );
        $this->assertTrue(
            $action->getOption(ReplaceBooleanOperatorsWithLogicalOperators::OPTION_REPLACE_AND),
            'Default value for replaceAnd is wrong'
        );
        $this->assertTrue(
            $action->getOption(ReplaceBooleanOperatorsWithLogicalOperators::OPTION_REPLACE_OR),
            'Default value for replaceOr is wrong'
        );
        $this->assertCount(3, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = [];
        $path = '/Action/ReplaceBooleanOperatorsWithLogicalOperators/';

        #0
        $data[] = [
            [ReplaceBooleanOperatorsWithLogicalOperators::OPTION_UPPERCASE => false],
            $this->getContainerFromFixture($path.'input0.php'),
            $this->getContainerFromFixture($path.'output0.php'),
        ];

        #1
        $data[] = [
            [ReplaceBooleanOperatorsWithLogicalOperators::OPTION_UPPERCASE => true],
            $this->getContainerFromFixture($path.'input1.php'),
            $this->getContainerFromFixture($path.'output1.php'),
        ];

        #2
        $data[] = [
            [ReplaceBooleanOperatorsWithLogicalOperators::OPTION_REPLACE_AND => false],
            $this->getContainerFromFixture($path.'input2.php'),
            $this->getContainerFromFixture($path.'output2.php'),
        ];

        #3
        $data[] = [
            [ReplaceBooleanOperatorsWithLogicalOperators::OPTION_REPLACE_OR => false],
            $this->getContainerFromFixture($path.'input3.php'),
            $this->getContainerFromFixture($path.'output3.php'),
        ];

        #3
        $data[] = [
            [ReplaceBooleanOperatorsWithLogicalOperators::OPTION_REPLACE_OR  => false,
             ReplaceBooleanOperatorsWithLogicalOperators::OPTION_REPLACE_AND => false,
            ],
            $this->getContainerFromFixture($path.'input4.php'),
            $this->getContainerFromFixture($path.'output4.php'),
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
        $action = new ReplaceBooleanOperatorsWithLogicalOperators($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
