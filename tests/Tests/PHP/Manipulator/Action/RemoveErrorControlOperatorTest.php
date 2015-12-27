<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveErrorControlOperator;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\RemoveErrorControlOperator
 */
class RemoveErrorControlOperatorTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new RemoveErrorControlOperator();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = [];

        $data['Remove ErrorControl Operator'] = 0;

        return $this->convertContainerFixtureToProviderData($data, '/Action/RemoveErrorControlOperator/');
    }

    /**
     * @dataProvider manipulateProvider
     *
     * @param TokenContainer $container
     * @param TokenContainer $expectedContainer
     */
    public function testManipulate(TokenContainer $container, TokenContainer $expectedContainer)
    {
        $manipulator = new RemoveErrorControlOperator();
        $manipulator->run($container);
        $this->assertTokenContainerMatch($expectedContainer, $container);
    }
}
