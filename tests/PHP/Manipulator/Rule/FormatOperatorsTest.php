<?php

/**
 * @group Rule_FormatOperators
 */
class PHP_Manipulator_Rule_FormatOperatorsTest extends TestCase
{

    /**
     * @covers PHP_Manipulator_Rule_FormatOperators::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Manipulator_Rule_FormatOperators();
        $this->assertType('array', $rule->getOption('beforeOperator'), 'Wrong default Option value for beforeOperator');
        $this->assertType('array', $rule->getOption('afterOperator'), 'Wrong default Option value for afterOperator');
        // @todo check number of elements, check all are operators ...
    }
    
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/FormatOperators/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
        );

        #1
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        return $data;
    }

    /**
     * @covers PHP_Manipulator_Rule_FormatOperators::applyRuleToTokens
     * @covers PHP_Manipulator_Rule_FormatOperators::<protected>
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Manipulator_Rule_FormatOperators($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}