<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';
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

        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'singleLineComment1'),
            $this->getTokenArrayFromFixtureFile($path . 'singleLineComment1Removed'),
        );

        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'singleLineComment2'),
            $this->getTokenArrayFromFixtureFile($path . 'singleLineComment2Removed'),
        );

        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'multiLineComment1'),
            $this->getTokenArrayFromFixtureFile($path . 'multiLineComment1Removed'),
        );

        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'multiLineComment2'),
            $this->getTokenArrayFromFixtureFile($path . 'multiLineComment2Removed'),
        );

        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'multiLineComment3'),
            $this->getTokenArrayFromFixtureFile($path . 'multiLineComment3Removed'),
        );

        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'docComment1'),
            $this->getTokenArrayFromFixtureFile($path . 'docComment1Removed'),
        );
        
        return $data;
    }

    /**
     *
     * @covers PHP_Formatter_Rule_RemoveComments::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Formatter_Rule_RemoveComments($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokensMatch($expectedTokens, $input, 'Wrong output');
    }
}