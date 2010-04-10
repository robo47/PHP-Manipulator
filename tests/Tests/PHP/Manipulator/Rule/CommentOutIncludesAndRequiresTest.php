<?php

namespace Tests\PHP\Manipulator\Rule;

use PHP\Manipulator\Rule\CommentOutIncludesAndRequires;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Rule_CommentOutIncludesAndRequires
 */
class CommentOutIncludesAndRequiresTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Rule\CommentOutIncludesAndRequires::init
     */
    public function testConstructorDefaults()
    {
        $rule = new CommentOutIncludesAndRequires();
        $this->assertTrue($rule->getOption('globalScopeOnly'), 'Wrong default Option value for globalScopeOnly');
    }

    /**
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/CommentOutIncludesAndRequires/';

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
     * @covers \PHP\Manipulator\Rule\CommentOutIncludesAndRequires::apply
     * @covers \PHP\Manipulator\Rule\CommentOutIncludesAndRequires::<protected>
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new CommentOutIncludesAndRequires($options);
        $rule->apply($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}