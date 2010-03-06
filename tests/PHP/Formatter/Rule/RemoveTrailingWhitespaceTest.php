<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';
require_once 'PHP/Formatter/Rule/RemoveTrailingWhitespace.php';

class PHP_Formatter_Rule_RemoveTrailingWhitespaceTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter_Rule_RemoveTrailingWhitespace::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Formatter_Rule_RemoveTrailingWhitespace();
        $removeEmptyLinesAtFileEnd = $rule->getOption('removeEmptyLinesAtFileEnd');
        $this->assertTrue($removeEmptyLinesAtFileEnd, 'Default Value for removeEmptyLinesAtFileEnd is wrong');
    }

    /**
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/RemoveTrailingWhitespace/';

        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'trailingWhitespace1'),
            $this->getTokenArrayFromFixtureFile($path . 'trailingWhitespace1Removed'),
        );
        
        return $data;
    }

    /**
     * @covers PHP_Formatter_Rule_RemoveTrailingWhitespace::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Formatter_Rule_RemoveTrailingWhitespace($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokensMatch($expectedTokens, $input, 'Wrong output');
    }
}