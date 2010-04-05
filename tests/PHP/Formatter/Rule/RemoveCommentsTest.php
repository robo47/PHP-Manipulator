<?php

/**
 * @group Rule_RemoveComments
 */
class PHP_Formatter_Rule_RemoveCommentsTest extends TestCase
{

    /**
     * @covers PHP_Formatter_Rule_RemoveComments::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Formatter_Rule_RemoveComments();
        $this->assertTrue($rule->getOption('removeDocComments'), 'Wrong default Option value for removeDocComments');
        $this->assertTrue($rule->getOption('removeStandardComments'), 'Wrong default Option value for removeStandardComments');
    }
    
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/RemoveComments/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
        );

        #1
        $data[] = array(
            array('removeDocComments' => false, 'removeStandardComments' => true),
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        #2
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
        );

        #3
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input3'),
            $this->getContainerFromFixture($path . 'output3'),
        );

        #4
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input4'),
            $this->getContainerFromFixture($path . 'output4'),
        );

        #5
        $data[] = array(
            array('removeDocComments' => true, 'removeStandardComments' => false),
            $this->getContainerFromFixture($path . 'input5'),
            $this->getContainerFromFixture($path . 'output5'),
        );

        #6
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input6'),
            $this->getContainerFromFixture($path . 'output6'),
        );

        #7
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input7'),
            $this->getContainerFromFixture($path . 'output7'),
        );

        return $data;
    }

    /**
     * @dataProvider ruleProvider
     * @covers PHP_Formatter_Rule_RemoveComments
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Formatter_Rule_RemoveComments($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}