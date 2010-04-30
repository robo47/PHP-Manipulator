<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ElseIfToElseAndIf;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\ElseIfToElseAndIf
 */
class ElseIfToElseAndIfTest extends \Tests\TestCase
{

    /**
     * @covers ElseIfToElseAndIf::init
     */
    public function testConstructorDefaults()
    {
        $action = new ElseIfToElseAndIf();
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/ElseIfToElseAndIf/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\ElseIfToElseAndIf
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new ElseIfToElseAndIf($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}