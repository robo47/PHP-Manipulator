<?php

/**
 * @group Rule_AsptagsToLongTags
 */
class PHP_Manipulator_Rule_AsptagsToLongTagsTest extends TestCase
{

    /**
     * @covers PHP_Manipulator_Rule_AsptagsToLongTags::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Manipulator_Rule_AsptagsToLongTags();
    }
    
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/AsptagsToLongTags/';

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
     * @covers PHP_Manipulator_Rule_AsptagsToLongTags::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $this->checkAsptags();
        
        $rule = new PHP_Manipulator_Rule_AsptagsToLongTags($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}