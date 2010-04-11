<?php

namespace Tests\PHP\Manipulator\Rule;

use PHP\Manipulator\Rule\RemoveTrailingWhitespace;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Rule
 * @group Rule\RemoveTrailingWhitespace
 */
class RemoveTrailingWhitespaceTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Rule\RemoveTrailingWhitespace::init
     */
    public function testConstructorDefaults()
    {
        $rule = new RemoveTrailingWhitespace();
        $removeEmptyLinesAtFileEnd = $rule->getOption('removeEmptyLinesAtFileEnd');
        $this->assertTrue($removeEmptyLinesAtFileEnd, 'Default Value for removeEmptyLinesAtFileEnd is wrong');
    }

    /**
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/RemoveTrailingWhitespace/';

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

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Rule\RemoveTrailingWhitespace::apply
     * @dataProvider ruleProvider
     * @param array $options
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new RemoveTrailingWhitespace($options);
        $rule->apply($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}