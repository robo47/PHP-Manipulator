<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ElseifToElseAndIf;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\ElseifToElseAndIf
 */
class ElseifToElseAndIfTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\ElseifToElseAndIf::init
     */
    public function testConstructorDefaults()
    {
        $action = new ElseifToElseAndIf();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/ElseifToElseAndIf/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\ElseifToElseAndIf
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new ElseifToElseAndIf($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}