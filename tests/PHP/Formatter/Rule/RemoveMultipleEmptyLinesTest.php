<?php

/**
 * @group Rule_RemoveMultipleEmptyLines
 */
class PHP_Formatter_Rule_RemoveMultipleEmptyLinesTest extends PHPFormatterTestCase
{

    /**
     * @covers PHP_Formatter_Rule_RemoveMultipleEmptyLines::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Formatter_Rule_RemoveMultipleEmptyLines();
        $this->assertEquals(2, $rule->getOption('maxEmptyLines'), 'Wrong default Option value for maxEmptyLines');
    }
    
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/RemoveMultipleEmptyLines/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'multipleEmptyLines1'),
            $this->getContainerFromFixture($path . 'multipleEmptyLines1Removed'),
        );

        #1
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'multipleEmptyLines2'),
            $this->getContainerFromFixture($path . 'multipleEmptyLines2Removed'),
        );

        return $data;
    }

    /**
     *
     * @covers PHP_Formatter_Rule_RemoveMultipleEmptyLines::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Formatter_Rule_RemoveMultipleEmptyLines($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}