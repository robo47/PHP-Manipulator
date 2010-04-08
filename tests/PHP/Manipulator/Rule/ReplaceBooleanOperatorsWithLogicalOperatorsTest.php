<?php

namespace Tests\PHP\Manipulator\Rule;

use PHP\Manipulator\Rule\ReplaceBooleanOperatorsWithLogicalOperators;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Rule_ReplaceBooleanOperatorsWithLogicalOperators
 */
class ReplaceBooleanOperatorsWithLogicalOperatorsTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Rule\ReplaceBooleanOperatorsWithLogicalOperators::init
     */
    public function testConstructorDefaults()
    {
        $rule = new ReplaceBooleanOperatorsWithLogicalOperators();
        $this->assertFalse($rule->getOption('uppercase'));
        $this->assertTrue($rule->getOption('replaceAnd'));
        $this->assertTrue($rule->getOption('replaceOr'));
    }

    /**
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/ReplaceBooleanOperatorsWithLogicalOperators/';

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
     * @covers \PHP\Manipulator\Rule\ReplaceBooleanOperatorsWithLogicalOperators::applyRuleToTokens
     * @covers \PHP\Manipulator\Rule\ReplaceBooleanOperatorsWithLogicalOperators::<protected>
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new ReplaceBooleanOperatorsWithLogicalOperators($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}