<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ElseAndIfToElseif;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\ElseAndIfToElseif
 */
class ElseAndIfToElseifTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new ElseAndIfToElseif();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data              = [];
        $data['Example 0'] = 0;

        return $this->convertContainerFixtureToProviderData($data, '/Action/ElseAndIfToElseif/');
    }

    /**
     * @dataProvider actionProvider
     *
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     */
    public function testAction($input, $expectedTokens)
    {
        $action = new ElseAndIfToElseif();
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
