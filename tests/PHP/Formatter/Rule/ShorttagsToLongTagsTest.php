<?php

class PHP_Formatter_Rule_ShorttagsToLongTagsTest extends PHPFormatterTestCase
{

    /**
     * @covers PHP_Formatter_Rule_ShorttagsToLongTags::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Formatter_Rule_ShorttagsToLongTags();
    }
    
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/ShorttagsToLongTags/';

        #0
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'input1'),
            $this->getTokenArrayFromFixtureFile($path . 'input1Removed'),
        );

        #1
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'input2'),
            $this->getTokenArrayFromFixtureFile($path . 'input2Removed'),
        );

        #2
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'input3'),
            $this->getTokenArrayFromFixtureFile($path . 'input3Removed'),
        );

        return $data;
    }


    /**
     * @covers PHP_Formatter_Rule_ShorttagsToLongTags::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $this->checkShorttags();
        
        $rule = new PHP_Formatter_Rule_ShorttagsToLongTags($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}