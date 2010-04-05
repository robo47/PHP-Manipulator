<?php

/**
 * @group Rule_ReplaceLogicalOperatorsWithBooleanOperators
 */
class PHP_Manipulator_Rule_ReplaceLogicalOperatorsWithBooleanOperatorsTest extends TestCase
{

    /**
     * @covers PHP_Manipulator_Rule_ReplaceLogicalOperatorsWithBooleanOperators::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Manipulator_Rule_ReplaceLogicalOperatorsWithBooleanOperators();
        $this->assertTrue($rule->getOption('replaceAnd'));
        $this->assertTrue($rule->getOption('replaceOr'));
    }

    /**
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/ReplaceLogicalOperatorsWithBooleanOperators/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
        );

        #1
        $data[] = array(
            array('replaceAnd' => false),
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        #2
        $data[] = array(
            array('replaceOr' => false),
            $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
        );

        #3
        $data[] = array(
            array('replaceOr' => false, 'replaceAnd' => false),
            $this->getContainerFromFixture($path . 'input3'),
            $this->getContainerFromFixture($path . 'output3'),
        );

        return $data;
    }

    /**
     * @covers PHP_Manipulator_Rule_ReplaceLogicalOperatorsWithBooleanOperators::applyRuleToTokens
     * @covers PHP_Manipulator_Rule_ReplaceLogicalOperatorsWithBooleanOperators::<protected>
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Manipulator_Rule_ReplaceLogicalOperatorsWithBooleanOperators($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}