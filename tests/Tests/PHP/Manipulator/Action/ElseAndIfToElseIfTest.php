<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ElseAndIfToElseIf;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\ElseAndIfToElseIf
 */
class ElseAndIfToElseIfTest extends \Tests\TestCase
{

    /**
     * @covers ElseAndIfToElseIf::init
     */
    public function testConstructorDefaults()
    {
        $action = new ElseAndIfToElseIf();
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/ElseAndIfToElseIf/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\ElseAndIfToElseIf
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new ElseAndIfToElseIf($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}