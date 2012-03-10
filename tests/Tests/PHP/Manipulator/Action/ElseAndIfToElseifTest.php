<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ElseAndIfToElseif;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\ElseAndIfToElseif
 */
class ElseAndIfToElseifTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\ElseAndIfToElseif::init
     */
    public function testConstructorDefaults()
    {
        $action = new ElseAndIfToElseif();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/ElseAndIfToElseif/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\ElseAndIfToElseif
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new ElseAndIfToElseif($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
