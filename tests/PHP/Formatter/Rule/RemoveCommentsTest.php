<?php

class PHP_Formatter_Rule_RemoveCommentsTest extends PHPFormatterTestCase
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
            $this->getContainerFromFixture($path . 'singleLineComment1'),
            $this->getContainerFromFixture($path . 'singleLineComment1Removed'),
        );

        #1
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'singleLineComment2'),
            $this->getContainerFromFixture($path . 'singleLineComment2Removed'),
        );

        #2
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'multiLineComment1'),
            $this->getContainerFromFixture($path . 'multiLineComment1Removed'),
        );

        #3
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'multiLineComment2'),
            $this->getContainerFromFixture($path . 'multiLineComment2Removed'),
        );

        #4
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'multiLineComment3'),
            $this->getContainerFromFixture($path . 'multiLineComment3Removed'),
        );

        #5
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'docComment1'),
            $this->getContainerFromFixture($path . 'docComment1Removed'),
        );

        #6
        $data[] = array(
            array('removeDocComments' => true, 'removeStandardComments' => false),
            $this->getContainerFromFixture($path . 'docCommentOnly1'),
            $this->getContainerFromFixture($path . 'docCommentOnly1Removed'),
        );

        #7
        $data[] = array(
            array('removeDocComments' => false, 'removeStandardComments' => true),
            $this->getContainerFromFixture($path . 'normalCommentOnly1'),
            $this->getContainerFromFixture($path . 'normalCommentOnly1Removed'),
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