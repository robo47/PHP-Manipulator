<?php

/**
 * @group __classname__
 */
class PHP_Formatter___classname__Test extends PHPFormatterTestCase
{

    /**
     * @covers PHP_Formatter___classname__::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Formatter___classname__();

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
     * @covers PHP_Formatter___classname__
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $this->markTestSkipped('not implemented yet');
        $rule = new PHP_Formatter___classname__($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}