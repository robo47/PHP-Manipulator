<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveErrorControlOperator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\RemoveErrorControlOperator
 */
class RemoveErrorControlOperatorTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();
        $path = '/Action/RemoveErrorControlOperator/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
            false
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers \PHP\Manipulator\Action\RemoveErrorControlOperator
     */
    public function testManipulate($container, $expectedContainer, $strict)
    {
        $manipulator = new RemoveErrorControlOperator();
        $manipulator->run($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}