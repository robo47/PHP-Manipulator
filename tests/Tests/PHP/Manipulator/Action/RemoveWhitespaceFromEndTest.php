<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveWhitespaceFromEnd;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\RemoveWhitespaceFromEnd
 */
class RemoveWhitespaceFromEndTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new RemoveWhitespaceFromEnd();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = [];

        $data['Example 0']                                     = 0;
        $data['Example 1']                                     = 1;
        $data['Test whitespace on end of html is removed too'] = 2;

        return $this->convertContainerFixtureToProviderData($data, '/Action/RemoveWhitespaceFromEnd/');
    }

    /**
     * @dataProvider manipulateProvider
     *
     * @param TokenContainer $container
     * @param TokenContainer $expectedContainer
     */
    public function testManipulate(TokenContainer $container, TokenContainer $expectedContainer)
    {
        $manipulator = new RemoveWhitespaceFromEnd();
        $manipulator->run($container);
        $this->assertTokenContainerMatch($expectedContainer, $container);
    }
}
