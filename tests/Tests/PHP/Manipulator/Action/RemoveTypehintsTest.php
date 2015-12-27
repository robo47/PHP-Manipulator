<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveTypehints;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\RemoveTypehints
 */
class RemoveTypehintsTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new RemoveTypehints();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = [];

        $data['Example 0']                      = 0;
        $data['Example 1']                      = 1;
        $data['Test = null not get\'s removed'] = 2;
        $data['Test it works with namespaces']  = 3;

        return $this->convertContainerFixtureToProviderData($data, '/Action/RemoveTypehints/');
    }

    /**
     * @dataProvider manipulateProvider
     *
     * @param TokenContainer $container
     * @param TokenContainer $expectedContainer
     */
    public function testManipulate(TokenContainer $container, TokenContainer $expectedContainer)
    {
        $manipulator = new RemoveTypehints();
        $manipulator->run($container);
        $this->assertTokenContainerMatch($expectedContainer, $container);
    }
}
