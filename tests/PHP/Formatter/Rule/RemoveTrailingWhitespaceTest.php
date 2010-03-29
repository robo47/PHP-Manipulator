<?php

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

        #0
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'trailingWhitespace1'),
            $this->getTokenArrayFromFixtureFile($path . 'trailingWhitespace1Removed'),
        );

//        #1
//        $data[] = array(
//            array(),
//            $this->getTokenArrayFromFixtureFile($path . 'trailingWhitespace2'),
//            $this->getTokenArrayFromFixtureFile($path . 'trailingWhitespace2Removed'),
//        );

        return $data;
    }

    /**
     * @covers PHP_Formatter_Rule_RemoveTrailingWhitespace::applyRuleToTokens
     * @dataProvider ruleProvider
     * @param array $options
     * @param PHP_Formatter_TokenContainer $input
     * @param PHP_Formatter_TokenContainer $expectedTokens
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Formatter_Rule_RemoveTrailingWhitespace($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}