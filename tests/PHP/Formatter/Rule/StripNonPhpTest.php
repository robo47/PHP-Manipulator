<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';
require_once 'PHP/Formatter/Rule/StripNonPhp.php';

class PHP_Formatter_Rule_StripNonPhpTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter_Rule_StripNonPhp::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Formatter_Rule_StripNonPhp();
    }

    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/StripNonPhp/';

        #0
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'stripnonphp1'),
            $this->getTokenArrayFromFixtureFile($path . 'stripnonphp1Removed'),
        );

        if (ini_get('short_open_tags') == true) {
            // @todo tests with shorttags
        }
        
        return $data;
    }

    /**
     *
     * @covers PHP_Formatter_Rule_StripNonPhp::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Formatter_Rule_StripNonPhp($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}