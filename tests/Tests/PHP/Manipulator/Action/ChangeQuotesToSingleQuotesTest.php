<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ChangeQuotesToSingleQuotes;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\ChangeQuotesToSingleQuotes
 */
class ChangeQuotesToSingleQuotesTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new ChangeQuotesToSingleQuotes();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data                                                        = [];
        $data['simple string']                                       = 0;
        $data['array-index']                                         = 1;
        $data['Test string containing variables is not "destroyed"'] =2;
        $data['Test strings containing linebreaks are not changed']  = 3;

        return $this->convertContainerFixtureToProviderData($data, '/Action/ChangeQuotesToSingleQuotes/');
    }

    /**
     * @dataProvider actionProvider
     *
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     */
    public function testAction(TokenContainer $input, TokenContainer $expectedTokens)
    {
        $action = new ChangeQuotesToSingleQuotes();
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
