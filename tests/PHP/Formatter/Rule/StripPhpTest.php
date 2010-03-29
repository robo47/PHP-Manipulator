<?php

class PHP_Formatter_Rule_StripPhpTest extends PHPFormatterTestCase
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
            $this->getTokenArrayFromFixtureFile($path . 'stripphp1'),
            $this->getTokenArrayFromFixtureFile($path . 'stripphp1Removed'),
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
}