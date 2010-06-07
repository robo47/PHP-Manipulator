<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveIndention;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\RemoveIndention
 */
class RemoveIndentionTest extends \Tests\TestCase
{
    /**
     * @covers \PHP\Manipulator\Action\RemoveIndention::init
     */
    public function testConstructorDefaults()
    {
        $action = new RemoveIndention();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/RemoveIndention/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        #1
        $data[] = array(
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
        );

        #2
        $data[] = array(
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\RemoveIndention::run
     * @dataProvider actionProvider
     */
    public function testAction($input, $expectedTokens)
    {
        $action = new RemoveIndention();
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}