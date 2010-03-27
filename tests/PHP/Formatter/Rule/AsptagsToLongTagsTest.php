<?php

require_once 'PHP/Formatter/Rule/AsptagsToLongTags.php';

class PHP_Formatter_Rule_AsptagsToLongTagsTest extends PHPFormatterTestCase
{

    /**
     * @covers PHP_Formatter_Rule_AsptagsToLongTags::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Formatter_Rule_AsptagsToLongTags();
    }
    
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/AsptagsToLongTags/';

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
     * @return boolean
     */
    protected function _aspTagsActivated()
    {
        return (bool) ini_get('asp_tags');
    }

    /**
     * @covers PHP_Formatter_Rule_AsptagsToLongTags::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        if (!$this->_aspTagsActivated()) {
            $this->markTestSkipped('Can\'t test AsptagsToLongTags-Rule with asp_tags deactivated');
        }
        $rule = new PHP_Formatter_Rule_AsptagsToLongTags($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}