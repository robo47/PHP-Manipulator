<?php

namespace Tests\PHP\Manipulator\Rule;

use PHP\Manipulator\Rule\StripNonPhp;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Rule_StripNonPhp
 */
class StripNonPhpTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/StripNonPhp/';

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
     * @covers \PHP\Manipulator\Rule\StripNonPhp::apply
     * @covers \PHP\Manipulator\Rule\StripNonPhp::<protected>
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new StripNonPhp($options);
        $rule->apply($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }

    /**
     * @return array
     */
    public function shortTagsOnlyRuleProvider()
    {
        $data = array();
        $path = '/Rule/StripNonPhp/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        return $data;
    }

    /**
     *
     * @covers \PHP\Manipulator\Rule\StripNonPhp::apply
     * @covers \PHP\Manipulator\Rule\StripNonPhp::<protected>
     * @dataProvider shortTagsOnlyRuleProvider
     */
    public function testRuleWithShorttags($options, $input, $expectedTokens)
    {
        $this->checkShorttags();

        $rule = new StripNonPhp($options);
        $rule->apply($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}