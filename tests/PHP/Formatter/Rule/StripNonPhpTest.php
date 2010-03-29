<?php

class PHP_Formatter_Rule_StripNonPhpTest extends PHPFormatterTestCase
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
            $this->getTokenArrayFromFixtureFile($path . 'input0'),
            $this->getTokenArrayFromFixtureFile($path . 'output0'),
        );

        return $data;
    }

    /**
     * @covers PHP_Formatter_Rule_StripNonPhp::applyRuleToTokens
     * @covers PHP_Formatter_Rule_StripNonPhp::<protected>
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Formatter_Rule_StripNonPhp($options);
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
            $this->getTokenArrayFromFixtureFile($path . 'input1'),
            $this->getTokenArrayFromFixtureFile($path . 'output1'),
        );

        return $data;
    }

    /**
     *
     * @covers PHP_Formatter_Rule_StripNonPhp::applyRuleToTokens
     * @covers PHP_Formatter_Rule_StripNonPhp::<protected>
     * @dataProvider shortTagsOnlyRuleProvider
     */
    public function testRuleWithShorttags($options, $input, $expectedTokens)
    {
        $this->checkShorttags();

        $rule = new PHP_Formatter_Rule_StripNonPhp($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}