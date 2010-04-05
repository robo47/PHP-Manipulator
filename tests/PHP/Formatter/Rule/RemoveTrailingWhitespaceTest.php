<?php

/**
 * @group Rule_RemoveTrailingWhitespace
 */
class PHP_Formatter_Rule_RemoveTrailingWhitespaceTest extends TestCase
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
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
        );

        #1
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

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