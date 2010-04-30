<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveIncludesAndRequires;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\RemoveIncludesAndRequires
 */
class RemoveIncludesAndRequiresTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\RemoveIncludesAndRequires::init
     */
    public function testConstructorDefaults()
    {
        $action = new RemoveIncludesAndRequires();
        $this->assertTrue($action->getOption('globalScopeOnly'), 'Wrong default Option value for globalScopeOnly');
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/RemoveIncludesAndRequires/';

        #0
        $data[] = array(
            array('globalScopeOnly' => false),
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
        );

        #1
        $data[] = array(
            array('globalScopeOnly' => true),
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        #2
        $data[] = array(
            array('globalScopeOnly' => true),
            $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\RemoveIncludesAndRequires
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new RemoveIncludesAndRequires($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}