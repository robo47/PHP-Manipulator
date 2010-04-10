<?php

namespace Tests\PHP\Manipulator\Rule;

use PHP\Manipulator\Rule\AsptagsToLongTags;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Rule_AsptagsToLongTags
 */
class AsptagsToLongTagsTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Rule\AsptagsToLongTags::init
     */
    public function testConstructorDefaults()
    {
        $rule = new AsptagsToLongTags();
    }

    /**
     * @return array
     */
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
     * @covers \PHP\Manipulator\Rule\AsptagsToLongTags::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $this->checkAsptags();

        $rule = new AsptagsToLongTags($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}