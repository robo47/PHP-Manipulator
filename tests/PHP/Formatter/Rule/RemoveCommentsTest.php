<?php

require_once 'PHP/Formatter/Rule/RemoveComments.php';

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
            $this->getTokenArrayFromFixtureFile($path . 'singleLineComment1'),
            $this->getTokenArrayFromFixtureFile($path . 'singleLineComment1Removed'),
        );

        #1
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'singleLineComment2'),
            $this->getTokenArrayFromFixtureFile($path . 'singleLineComment2Removed'),
        );

        #2
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'multiLineComment1'),
            $this->getTokenArrayFromFixtureFile($path . 'multiLineComment1Removed'),
        );

        #3
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'multiLineComment2'),
            $this->getTokenArrayFromFixtureFile($path . 'multiLineComment2Removed'),
        );

        #4
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'multiLineComment3'),
            $this->getTokenArrayFromFixtureFile($path . 'multiLineComment3Removed'),
        );

        #5
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'docComment1'),
            $this->getTokenArrayFromFixtureFile($path . 'docComment1Removed'),
        );

        #6
        $data[] = array(
            array('removeDocComments' => true, 'removeStandardComments' => false),
            $this->getTokenArrayFromFixtureFile($path . 'docCommentOnly1'),
            $this->getTokenArrayFromFixtureFile($path . 'docCommentOnly1Removed'),
        );

        #7
        $data[] = array(
            array('removeDocComments' => false, 'removeStandardComments' => true),
            $this->getTokenArrayFromFixtureFile($path . 'normalCommentOnly1'),
            $this->getTokenArrayFromFixtureFile($path . 'normalCommentOnly1Removed'),
        );
        
        return $data;
    }

    /**
     *
     * @covers PHP_Formatter_Rule_RemoveComments::applyRuleToTokens
     * @covers PHP_Formatter_Rule_RemoveComments::<protected>
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Formatter_Rule_RemoveComments($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}