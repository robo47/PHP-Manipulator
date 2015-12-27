<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ElseifToElseAndIf;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\ElseifToElseAndIf
 */
class ElseifToElseAndIfTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new ElseifToElseAndIf();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data              = [];
        $data['Example 0'] = 0;

        return $this->convertContainerFixtureToProviderData($data, '/Action/ElseifToElseAndIf/');
    }

    /**
     * @dataProvider actionProvider
     *
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     */
    public function testAction($input, $expectedTokens)
    {
        $action = new ElseifToElseAndIf();
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
