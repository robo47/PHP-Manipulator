<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\FormatOperators;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\FormatOperators
 */
class FormatOperatorsTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\FormatOperators::init
     */
    public function testConstructorDefaults()
    {
        $action = new FormatOperators();
        $this->assertType('array', $action->getOption('beforeOperator'), 'Default value for beforeOperator is wrong');
        $this->assertType('array', $action->getOption('afterOperator'), 'Default value for afterOperator is wrong');
        // @todo check number of elements, check all are operators ...
        $this->assertCount(2, $action->getOptions());
    }

    /**
     *
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/FormatOperators/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        #1
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\FormatOperators::run
     * @covers \PHP\Manipulator\Action\FormatOperators::<protected>
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new FormatOperators($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}