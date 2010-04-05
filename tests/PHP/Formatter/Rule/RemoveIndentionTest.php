<?php

/**
 * @group Rule_RemoveIndention
 */
class PHP_Formatter_Rule_RemoveIndentionTest extends TestCase
{
    /**
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/RemoveIndention/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
        );

        #1
        $data[] = array(
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        #2
        $data[] = array(
            $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
        );

        return $data;
    }

    /**
     *
     * @covers PHP_Formatter_Rule_RemoveIndention::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($input, $expectedTokens)
    {
        $rule = new PHP_Formatter_Rule_RemoveIndention();
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
?>
