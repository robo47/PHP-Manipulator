<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ShorttagsToLongtags;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers \PHP\Manipulator\Action\ShorttagsToLongtags
 */
class ShorttagsToLongtagsTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new ShorttagsToLongtags();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $lines=[];
        $path = '/Action/ShorttagsToLongtags/';

        $lines['Example 0'] = 0;
        $lines['Example 1'] = 1;
        $lines['Example 2'] = 2;

        return $this->convertContainerFixtureToProviderData($lines, $path);
    }
    /**
     * @dataProvider actionProvider
     *
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     */
    public function testAction($input, $expectedTokens)
    {
        $this->checkShorttags();

        $action = new ShorttagsToLongtags();
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
