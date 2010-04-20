<?php

namespace Tests\PHP\Manipulator\Rule;

use PHP\Manipulator\Rule\RemoveIncludesAndRequires;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Rule\RemoveIncludesAndRequires
 */
class RemoveIncludesAndRequiresTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Rule\RemoveIncludesAndRequires::init
     */
    public function testConstructorDefaults()
    {
        $rule = new RemoveIncludesAndRequires();
        $this->assertTrue($rule->getOption('globalScopeOnly'), 'Wrong default Option value for globalScopeOnly');
    }

    /**
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/RemoveIncludesAndRequires/';

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
     * @covers \PHP\Manipulator\Rule\RemoveIncludesAndRequires
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new RemoveIncludesAndRequires($options);
        $rule->apply($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}