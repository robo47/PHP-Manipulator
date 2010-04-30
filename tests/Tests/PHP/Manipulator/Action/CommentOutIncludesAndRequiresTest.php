<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\CommentOutIncludesAndRequires;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\CommentOutIncludesAndRequires
 */
class CommentOutIncludesAndRequiresTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\CommentOutIncludesAndRequires::init
     */
    public function testConstructorDefaults()
    {
        $action = new CommentOutIncludesAndRequires();
        $this->assertTrue($action->getOption('globalScopeOnly'), 'Wrong default Option value for globalScopeOnly');
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/CommentOutIncludesAndRequires/';

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
     * @covers \PHP\Manipulator\Action\CommentOutIncludesAndRequires::run
     * @covers \PHP\Manipulator\Action\CommentOutIncludesAndRequires::<protected>
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new CommentOutIncludesAndRequires($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}