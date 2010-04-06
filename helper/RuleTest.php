<?php

namespace Tests\PHP\Manipulator\Rule;

use PHP\Manipulator\Rule\__classname__;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Rule___classname__
 */
class __classname__Test extends \Tests\TestCase
{

    /**
     * @covers __classname__::init
     */
    public function testConstructorDefaults()
    {
        $rule = new __classname__();
// @todo test for options ?
    }

    /**
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();
        $path = '/__path__/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        return $data;
    }

    /**
     * @covers __completeclassname__
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $this->markTestSkipped('not implemented yet');
        $rule = new __classname__($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}