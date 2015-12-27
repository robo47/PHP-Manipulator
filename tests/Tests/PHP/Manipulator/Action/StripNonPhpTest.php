<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\StripNonPhp;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\StripNonPhp
 */
class StripNonPhpTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new StripNonPhp();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data              = [];
        $data['Example 0'] = 0;

        return $this->convertContainerFixtureToProviderData($data, '/Action/StripNonPhp/');
    }

    /**
     * @dataProvider actionProvider
     *
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     */
    public function testAction(TokenContainer $input, TokenContainer $expectedTokens)
    {
        $action = new StripNonPhp();
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }

    /**
     * @return array
     */
    public function shortTagsOnlyactionProvider()
    {
        $data              = [];
        $data['Example 1'] = 1;

        return $this->convertContainerFixtureToProviderData($data, '/Action/StripNonPhp/');
    }

    /**
     * @dataProvider shortTagsOnlyactionProvider
     *
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     */
    public function testActionWithShorttags(TokenContainer $input, TokenContainer $expectedTokens)
    {
        $this->checkShorttags();

        $action = new StripNonPhp();
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
