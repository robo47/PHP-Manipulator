<?php
namespace Tests\PHP\Manipulator\Rule;

use PHP\Manipulator\Rule\FormatOperators;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Rule_FormatOperators
 */
class FormatOperatorsTest extends \Tests\TestCase
{

    /**
     * @covers PHP\Manipulator\Rule\FormatOperators::init
     */
    public function testConstructorDefaults()
    {
        $rule = new FormatOperators();
        $this->assertType('array', $rule->getOption('beforeOperator'), 'Wrong default Option value for beforeOperator');
        $this->assertType('array', $rule->getOption('afterOperator'), 'Wrong default Option value for afterOperator');
        // @todo check number of elements, check all are operators ...
    }
    
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/FormatOperators/';

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
     * @covers PHP\Manipulator\Rule\FormatOperators::applyRuleToTokens
     * @covers PHP\Manipulator\Rule\FormatOperators::<protected>
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new FormatOperators($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}