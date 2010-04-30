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
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/RemoveIndention/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
        );

        #1
        $data[] = array(
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        #2
        $data[] = array(
            $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
        );

        return $data;
    }

    /**
     *
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

?>
