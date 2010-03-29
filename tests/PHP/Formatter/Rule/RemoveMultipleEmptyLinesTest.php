<?php

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
            $this->getTokenArrayFromFixtureFile($path . 'multipleEmptyLines1'),
            $this->getTokenArrayFromFixtureFile($path . 'multipleEmptyLines1Removed'),
        );

        #1
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'multipleEmptyLines2'),
            $this->getTokenArrayFromFixtureFile($path . 'multipleEmptyLines2Removed'),
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