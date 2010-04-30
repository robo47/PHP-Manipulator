<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ReplaceBooleanOperatorsWithLogicalOperators;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\ReplaceBooleanOperatorsWithLogicalOperators
 */
class ReplaceBooleanOperatorsWithLogicalOperatorsTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\ReplaceBooleanOperatorsWithLogicalOperators::init
     */
    public function testConstructorDefaults()
    {
        $action = new ReplaceBooleanOperatorsWithLogicalOperators();
        $this->assertFalse($action->getOption('uppercase'));
        $this->assertTrue($action->getOption('replaceAnd'));
        $this->assertTrue($action->getOption('replaceOr'));
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/ReplaceBooleanOperatorsWithLogicalOperators/';

        #0
        $data[] = array(
            array('uppercase' => false),
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
        );

        #1
        $data[] = array(
            array('uppercase' => true),
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        #2
        $data[] = array(
            array('replaceAnd' => false),
            $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
        );

        #3
        $data[] = array(
            array('replaceOr' => false),
            $this->getContainerFromFixture($path . 'input3'),
            $this->getContainerFromFixture($path . 'output3'),
        );

        #3
        $data[] = array(
            array('replaceOr' => false, 'replaceAnd' => false),
            $this->getContainerFromFixture($path . 'input4'),
            $this->getContainerFromFixture($path . 'output4'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\ReplaceBooleanOperatorsWithLogicalOperators::run
     * @covers \PHP\Manipulator\Action\ReplaceBooleanOperatorsWithLogicalOperators::<protected>
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new ReplaceBooleanOperatorsWithLogicalOperators($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}