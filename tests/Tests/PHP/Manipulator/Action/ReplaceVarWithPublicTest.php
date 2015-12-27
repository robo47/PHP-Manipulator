<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ReplaceVarWithPublic;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\ReplaceVarWithPublic
 */
class ReplaceVarWithPublicTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new ReplaceVarWithPublic();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data              = [];
        $data['Example 0'] = 0;

        return $this->convertContainerFixtureToProviderData($data, '/Action/ReplaceVarWithPublic/');
    }

    /**
     * @dataProvider manipulateProvider
     *
     * @param TokenContainer $container
     * @param TokenContainer $expectedContainer
     */
    public function testManipulate(TokenContainer $container, TokenContainer $expectedContainer)
    {
        $manipulator = new ReplaceVarWithPublic();
        $manipulator->run($container);
        $this->assertTokenContainerMatch($expectedContainer, $container);
    }
}
