<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\AddPublicKeyword;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\AddPublicKeyword
 */
class AddPublicKeywordTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new AddPublicKeyword();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $lines                                                                            = [];
        $lines['Test']                                                                    = 0;
        $lines['Test it only adds to methods, not functions']                             = 1;
        $lines['Test it only adds public to methods and not to functions inside methods'] = 2;
        $lines['Test it not adds public to anonymous functions']                          = 3;
        $lines['Test it works with interfaces too']                                       = 4;

        return $this->convertContainerFixtureToProviderData($lines, '/Action/AddPublicKeyword/');
    }

    /**
     * @dataProvider actionProvider
     *
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     */
    public function testAction(TokenContainer $input, TokenContainer $expectedTokens)
    {
        $action = new AddPublicKeyword();
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
