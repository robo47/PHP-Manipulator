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
     * @covers \PHP\Manipulator\Action\RemoveWhitespaceFromEnd::init
     */
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
        $data = array();
        $path = '/Action/RemoveWhitespaceFromEnd/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
            false
        );

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
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