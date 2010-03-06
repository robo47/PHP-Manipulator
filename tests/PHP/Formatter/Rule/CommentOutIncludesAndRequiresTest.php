<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';
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

    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/CommentOutIncludesAndRequires/';

        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'singleLineComment1'),
            $this->getTokenArrayFromFixtureFile($path . 'singleLineComment1Removed'),
        );

        return $data;
    }

    /**
     *
     * @covers PHP_Formatter_Rule_CommentOutIncludesAndRequires::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Formatter_Rule_CommentOutIncludesAndRequires($options);
        $rule->applyRuleToTokens($input);

        $this->assertTokensMatch($expectedTokens, $input, 'Wrong output');
    }
}