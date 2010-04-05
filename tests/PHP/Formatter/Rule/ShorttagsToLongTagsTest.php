<?php

/**
 * @group Rule_ShorttagsToLongTags
 */
class PHP_Formatter_Rule_ShorttagsToLongTagsTest extends TestCase
{

    /**
     * @covers PHP_Formatter_Rule_ShorttagsToLongTags::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Formatter_Rule_ShorttagsToLongTags();
    }

    /**
     *
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/ShorttagsToLongTags/';

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

        #2
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
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