<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveIndention;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\RemoveIndention
 */
class RemoveIndentionTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new RemoveIndention();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = [];

        $data['Example 0'] = 0;
        $data['Example 1'] = 1;
        $data['Example 2'] = 2;

        return $this->convertContainerFixtureToProviderData($data, '/Action/RemoveIndention/');
    }

    /**
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     *
     * @dataProvider actionProvider
     */
    public function testAction(TokenContainer $input, TokenContainer $expectedTokens)
    {
        $action = new RemoveIndention();
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
