<?php

namespace Tests\PHP\Manipulator\Rule;

use PHP\Manipulator\Rule\ShorttagsToLongTags;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Rule
 * @group Rule\ShorttagsToLongTags
 */
class ShorttagsToLongTagsTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Rule\ShorttagsToLongTags::init
     */
    public function testConstructorDefaults()
    {
        $rule = new ShorttagsToLongTags();
    }

    /**
     *
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/ShorttagsToLongTags/';

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
     * @covers \PHP\Manipulator\Rule\ShorttagsToLongTags::apply
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $this->checkShorttags();

        $rule = new ShorttagsToLongTags($options);
        $rule->apply($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}