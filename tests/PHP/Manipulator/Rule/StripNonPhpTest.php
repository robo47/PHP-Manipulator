<?php

/**
 * @group Rule_StripNonPhp
 */
class PHP_Manipulator_Rule_StripNonPhpTest extends TestCase
{
    /**
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/StripNonPhp/';

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
     * @covers PHP_Manipulator_Rule_StripNonPhp::applyRuleToTokens
     * @covers PHP_Manipulator_Rule_StripNonPhp::<protected>
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Manipulator_Rule_StripNonPhp($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }

    /**
     * @return array
     */
    public function shortTagsOnlyRuleProvider()
    {
        $data = array();
        $path = '/Rule/StripNonPhp/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        return $data;
    }

    /**
     *
     * @covers PHP_Manipulator_Rule_StripNonPhp::applyRuleToTokens
     * @covers PHP_Manipulator_Rule_StripNonPhp::<protected>
     * @dataProvider shortTagsOnlyRuleProvider
     */
    public function testRuleWithShorttags($options, $input, $expectedTokens)
    {
        $this->checkShorttags();

        $rule = new PHP_Manipulator_Rule_StripNonPhp($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}