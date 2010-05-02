<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\__classname__;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\__classname__
 */
class __classname__Test extends \Tests\TestCase
{

    /**
     * @covers __completeclassname__::init
     */
    public function testConstructorDefaults()
    {
        $action = new __classname__();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/__path__/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        return $data;
    }

    /**
     * @covers __completeclassname__
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new __classname__($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}