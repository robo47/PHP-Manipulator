<?php

namespace Tests\PHP\Manipulator\Rule;

use PHP\Manipulator\Rule\RemoveMultipleEmptyLines;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Rule
 * @group Rule\RemoveMultipleEmptyLines
 */
class RemoveMultipleEmptyLinesTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Rule\RemoveMultipleEmptyLines::init
     */
    public function testConstructorDefaults()
    {
        $rule = new RemoveMultipleEmptyLines();
        $this->assertEquals(2, $rule->getOption('maxEmptyLines'), 'Wrong default Option value for maxEmptyLines');
    }
    
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/RemoveMultipleEmptyLines/';

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
     *
     * @covers \PHP\Manipulator\Rule\RemoveMultipleEmptyLines::apply
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new RemoveMultipleEmptyLines($options);
        $rule->apply($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}