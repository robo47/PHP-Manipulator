<?php
namespace Tests\PHP\Manipulator\Rule;

use PHP\Manipulator\Rule\RemoveIndention;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Rule_RemoveIndention
 */
class RemoveIndentionTest extends \Tests\TestCase
{
    /**
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/RemoveIndention/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
        );

        #1
        $data[] = array(
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        #2
        $data[] = array(
            $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
        );

        return $data;
    }

    /**
     *
     * @covers PHP\Manipulator\Rule\RemoveIndention::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($input, $expectedTokens)
    {
        $rule = new RemoveIndention();
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
?>
