<?php

require_once 'PHP/Formatter/Rule/ReplaceLogicalOperatorsWithBooleanOperators.php';

class PHP_Formatter_Rule_ReplaceLogicalOperatorsWithBooleanOperatorsTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter_Rule_ReplaceLogicalOperatorsWithBooleanOperators::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Formatter_Rule_ReplaceLogicalOperatorsWithBooleanOperators();
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
            $this->getTokenArrayFromFixtureFile($path . 'input1'),
            $this->getTokenArrayFromFixtureFile($path . 'output1'),
        );

        #1
        $data[] = array(
            array('replaceAnd' => false),
            $this->getTokenArrayFromFixtureFile($path . 'input1'),
            $this->getTokenArrayFromFixtureFile($path . 'output2'),
        );

        #2
        $data[] = array(
            array('replaceOr' => false),
            $this->getTokenArrayFromFixtureFile($path . 'input1'),
            $this->getTokenArrayFromFixtureFile($path . 'output3'),
        );

        #3
        $data[] = array(
            array('replaceOr' => false, 'replaceAnd' => false),
            $this->getTokenArrayFromFixtureFile($path . 'input1'),
            $this->getTokenArrayFromFixtureFile($path . 'output4'),
        );

        return $data;
    }

    /**
     * @covers PHP_Formatter_Rule_ReplaceLogicalOperatorsWithBooleanOperators::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Formatter_Rule_ReplaceLogicalOperatorsWithBooleanOperators($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}