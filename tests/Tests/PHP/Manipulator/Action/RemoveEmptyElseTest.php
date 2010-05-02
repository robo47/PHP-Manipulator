<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveEmptyElse;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\RemoveEmptyElse
 */
class RemoveEmptyElseTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\RemoveEmptyElse::init
     */
    public function testConstructorDefaults()
    {
        $action = new RemoveEmptyElse();
        $this->assertFalse($action->getOption('ignoreComments'), 'Default value for ignoreComments is wrong');
        $this->assertCount(1, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/RemoveEmptyElse/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        #1 Don't Remove else containing comment
        $data[] = array(
            array('ignoreComments' => false),
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
        );

        #2 Remove Else even if it contains a comment
        $data[] = array(
            array('ignoreComments' => true),
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
        );

        #3 Empty else Nested inside other else
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input3.php'),
            $this->getContainerFromFixture($path . 'output3.php'),
        );

        #4 Alternate syntax with endif;
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input4.php'),
            $this->getContainerFromFixture($path . 'output4.php'),
        );

        #5 Nested Alternate syntax with endif;
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input5.php'),
            $this->getContainerFromFixture($path . 'output5.php'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\RemoveEmptyElse
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new RemoveEmptyElse($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}