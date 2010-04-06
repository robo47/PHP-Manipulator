<?php

namespace Tests\PHP\Manipulator\Rule;

use PHP\Manipulator\Rule\StripPhp;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Rule_StripPhp
 */
class StripPhpTest extends \Tests\TestCase
{

    /**
     * @covers PHP\Manipulator\Rule\StripPhp::init
     */
    public function testConstructorDefaults()
    {
        $rule = new StripPhp();
    }
    
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/StripPhp/';

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
     * @covers PHP\Manipulator\Rule\StripPhp::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {

        $rule = new StripPhp($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }

    /**
     * @return array
     */
    public function shortTagsOnlyRuleProvider()
    {
        $data = array();
        $path = '/Rule/StripPhp/';

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
     * @covers PHP\Manipulator\Rule\StripPhp::applyRuleToTokens
     * @covers PHP\Manipulator\Rule\StripPhp::<protected>
     * @dataProvider shortTagsOnlyRuleProvider
     */
    public function testRuleWithShorttags($options, $input, $expectedTokens)
    {
        $this->checkShorttags();

        $rule = new StripPhp($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}