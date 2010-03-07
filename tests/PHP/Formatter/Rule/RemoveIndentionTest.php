<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';
require_once 'PHP/Formatter/Rule/RemoveIndention.php';

class PHP_Formatter_Rule_RemoveIndentionTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter_Rule_RemoveIndention::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Formatter_Rule_RemoveIndention();
    }

    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/RemoveIndention/';

        #0
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'test1'),
            $this->getTokenArrayFromFixtureFile($path . 'test1Removed'),
        );
        
        return $data;
    }

    /**
     *
     * @covers PHP_Formatter_Rule_RemoveIndention::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Formatter_Rule_RemoveIndention($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}