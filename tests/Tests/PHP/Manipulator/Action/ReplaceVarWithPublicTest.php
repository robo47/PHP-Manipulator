<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ReplaceVarWithPublic;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\ReplaceVarWithPublic
 */
class ReplaceVarWithPublicTest extends \Tests\TestCase
{
    /**
     * @covers \PHP\Manipulator\Action\ReplaceVarWithPublic::init
     */
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
        $data = array();
        $path = '/Action/ReplaceVarWithPublic/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
            false
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\ReplaceVarWithPublic
     *      * @dataProvider manipulateProvider
     */
    public function testManipulate($container, $expectedContainer, $strict)
    {
        $manipulator = new ReplaceVarWithPublic();
        $manipulator->run($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}