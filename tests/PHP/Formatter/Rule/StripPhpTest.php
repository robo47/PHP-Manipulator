<?php

/**
 * @group Rule_StripPhp
 */
class PHP_Formatter_Rule_StripPhpTest extends TestCase
{

    /**
     * @covers PHP_Formatter_Rule_StripPhp::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Formatter_Rule_StripPhp();
    }
    
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/StripPhp/';

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
     *
     * @covers PHP_Formatter_Rule_StripPhp::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {

        $rule = new PHP_Formatter_Rule_StripPhp($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }

    /**
     * @return array
     */
    public function shortTagsOnlyRuleProvider()
    {
        $data = array();
        $path = '/Rule/StripPhp/';

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
     * @covers PHP_Formatter_Rule_StripPhp::applyRuleToTokens
     * @covers PHP_Formatter_Rule_StripPhp::<protected>
     * @dataProvider shortTagsOnlyRuleProvider
     */
    public function testRuleWithShorttags($options, $input, $expectedTokens)
    {
        $this->checkShorttags();

        $rule = new PHP_Formatter_Rule_StripPhp($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}