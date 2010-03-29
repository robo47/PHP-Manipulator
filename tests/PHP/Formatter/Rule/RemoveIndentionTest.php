<?php

/**
 * @group Rule_RemoveIndention
 */
class PHP_Formatter_Rule_RemoveIndentionTest extends PHPFormatterTestCase
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
            $this->getTokenArrayFromFixtureFile($path . 'input0'),
            $this->getTokenArrayFromFixtureFile($path . 'output0'),
        );

        #1
        $data[] = array(
            $this->getTokenArrayFromFixtureFile($path . 'input1'),
            $this->getTokenArrayFromFixtureFile($path . 'output1'),
        );

        #2
        $data[] = array(
            $this->getTokenArrayFromFixtureFile($path . 'input2'),
            $this->getTokenArrayFromFixtureFile($path . 'output2'),
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
