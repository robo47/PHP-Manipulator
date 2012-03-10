<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ReplaceLogicalOperatorsWithBooleanOperators;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\ReplaceLogicalOperatorsWithBooleanOperators
 */
class ReplaceLogicalOperatorsWithBooleanOperatorsTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\ReplaceLogicalOperatorsWithBooleanOperators::init
     */
    public function testConstructorDefaults()
    {
        $action = new ReplaceLogicalOperatorsWithBooleanOperators();
        $this->assertTrue($action->getOption('replaceAnd'), 'Default value for replaceAnd is wrong');
        $this->assertTrue($action->getOption('replaceOr'), 'Default value for replaceOr is wrong');
        $this->assertCount(2, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/ReplaceLogicalOperatorsWithBooleanOperators/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        #1
        $data[] = array(
            array('replaceAnd' => false),
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
        );

        #2
        $data[] = array(
            array('replaceOr' => false),
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
        );

        #3
        $data[] = array(
            array('replaceOr' => false, 'replaceAnd' => false),
            $this->getContainerFromFixture($path . 'input3.php'),
            $this->getContainerFromFixture($path . 'output3.php'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\ReplaceLogicalOperatorsWithBooleanOperators::run
     * @covers \PHP\Manipulator\Action\ReplaceLogicalOperatorsWithBooleanOperators::<protected>
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new ReplaceLogicalOperatorsWithBooleanOperators($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
