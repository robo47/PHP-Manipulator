<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\FormatOperators;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\FormatOperators
 */
class FormatOperatorsTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new FormatOperators();
        $this->assertInternalType(
            'array',
            $action->getOption(FormatOperators::OPTION_BEFORE_OPERATOR),
            'Default value for beforeOperator is wrong'
        );
        $this->assertInternalType(
            'array',
            $action->getOption(FormatOperators::OPTION_AFTER_OPERATOR),
            'Default value for afterOperator is wrong'
        );

        $this->assertCount(28, $action->getOption(FormatOperators::OPTION_BEFORE_OPERATOR), 'Wrong number of operators');
        $this->assertCount(28, $action->getOption(FormatOperators::OPTION_AFTER_OPERATOR), 'Wrong number of operators');

        $this->assertCount(2, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = [];

        $data['Example 0'] = 0;
        $data['Example 1'] = 1;

        return $this->convertContainerFixtureToProviderData($data, '/Action/FormatOperators/');
    }

    /**
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     *
     * @dataProvider actionProvider
     */
    public function testAction(TokenContainer $input, TokenContainer $expectedTokens)
    {
        $action = new FormatOperators();
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
