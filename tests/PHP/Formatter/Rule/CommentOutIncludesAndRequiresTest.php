<?php

require_once 'PHP/Formatter/Rule/CommentOutIncludesAndRequires.php';

class PHP_Formatter_Rule_CommentOutIncludesAndRequiresTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter_Rule_CommentOutIncludesAndRequires::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Formatter_Rule_CommentOutIncludesAndRequires();
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
            $this->getTokenArrayFromFixtureFile($path . 'requiresAndIncludes1'),
            $this->getTokenArrayFromFixtureFile($path . 'requiresAndIncludes1Removed'),
        );

//        #1
//        $data[] = array(
//            array('globalScopeOnly' => true),
//            $this->getTokenArrayFromFixtureFile($path . 'requiresAndIncludes2'),
//            $this->getTokenArrayFromFixtureFile($path . 'requiresAndIncludes2Removed'),
//        );

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