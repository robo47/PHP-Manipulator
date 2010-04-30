<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveMultipleEmptyLines;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\RemoveMultipleEmptyLines
 */
class RemoveMultipleEmptyLinesTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\RemoveMultipleEmptyLines::init
     */
    public function testConstructorDefaults()
    {
        $action = new RemoveMultipleEmptyLines();
        $this->assertEquals(2, $action->getOption('maxEmptyLines'), 'Wrong default Option value for maxEmptyLines');
    }
    
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/RemoveMultipleEmptyLines/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
        );

        #1
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        return $data;
    }

    /**
     *
     * @covers \PHP\Manipulator\Action\RemoveMultipleEmptyLines::run
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new RemoveMultipleEmptyLines($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}