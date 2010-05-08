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
        $this->assertEquals(2, $action->getOption('maxEmptyLines'), 'Default value for maxEmptyLines is wrong');
        $this->assertCount(1, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/RemoveMultipleEmptyLines/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        #1
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
        );

        #2
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
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