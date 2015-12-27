<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveLeadingAndTrailingEmptyLinesInPhpdoc;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\RemoveLeadingAndTrailingEmptyLinesInPhpdoc
 */
class RemoveLeadingAndTrailingEmptyLinesInPhpdocTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new RemoveLeadingAndTrailingEmptyLinesInPhpdoc();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = [];

        $data['Leading empty line']                            = 0;
        $data['Trailing']                                      = 1;
        $data['Leading and Trailing']                          = 2;
        $data['Leading and Trailing and empty line in middle'] = 3;

        return $this->convertContainerFixtureToProviderData(
            $data,
            '/Action/RemoveLeadingAndTrailingEmptyLinesInPhpdoc/'
        );
    }

    /**
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     *
     * @dataProvider actionProvider
     */
    public function testAction(TokenContainer $input, TokenContainer $expectedTokens)
    {
        $action = new RemoveLeadingAndTrailingEmptyLinesInPhpdoc();
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
