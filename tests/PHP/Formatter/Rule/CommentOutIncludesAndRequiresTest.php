<?php

/**
 * @group Rule_CommentOutIncludesAndRequires
 */
class PHP_Formatter_Rule_CommentOutIncludesAndRequiresTest extends PHPFormatterTestCase
{

    /**
     * @covers PHP_Formatter_Rule_CommentOutIncludesAndRequires::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Formatter_Rule_CommentOutIncludesAndRequires();
        $this->assertTrue($rule->getOption('globalScopeOnly'), 'Wrong default Option value for globalScopeOnly');
    }

    /**
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/CommentOutIncludesAndRequires/';

        #0
        $data[] = array(
            array('globalScopeOnly' => false),
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
        );

        #1
        $data[] = array(
            array('globalScopeOnly' => true),
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        #2
        $data[] = array(
            array('globalScopeOnly' => true),
            $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
        );

        return $data;
    }

    /**
     * @covers PHP_Formatter_Rule_CommentOutIncludesAndRequires::applyRuleToTokens
     * @covers PHP_Formatter_Rule_CommentOutIncludesAndRequires::<protected>
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Formatter_Rule_CommentOutIncludesAndRequires($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}