<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveWhitespaceFromEnd;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\RemoveWhitespaceFromEnd
 */
class RemoveWhitespaceFromEndTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();
        $path = '/Action/RemoveWhitespaceFromEnd/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
            false
        );

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
            false
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers \PHP\Manipulator\Action\RemoveWhitespaceFromEnd
     */
    public function testManipulate($container, $expectedContainer, $strict)
    {
        $manipulator = new RemoveWhitespaceFromEnd();
        $manipulator->run($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}