<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\OldStyleConstructorReplacer;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\OldStyleConstructorReplacer
 */
class OldStyleConstructorReplacerTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new OldStyleConstructorReplacer();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data                                 = [];
        $data['Example 0']                    = 0;
        $data['2 Classes']                    = 1;
        $data['A function outside the class'] = 2;

        return $this->convertContainerFixtureToProviderData($data, '/Action/OldStyleConstructorReplacer/');
    }

    /**
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     *
     * @dataProvider actionProvider
     */
    public function testAction(TokenContainer $input, TokenContainer $expectedTokens)
    {
        $action = new OldStyleConstructorReplacer();
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
