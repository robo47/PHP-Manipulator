<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\OldStyleConstructorReplacer;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\OldStyleConstructorReplacer
 */
class OldStyleConstructorReplacerTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\OldStyleConstructorReplacer::init
     */
    public function testConstructorDefaults()
    {
        $action = new OldStyleConstructorReplacer();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/OldStyleConstructorReplacer/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        #1 2 Classes
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
        );

        #2 A method outside the class
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\OldStyleConstructorReplacer
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new OldStyleConstructorReplacer($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}