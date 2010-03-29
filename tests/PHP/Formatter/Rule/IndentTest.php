<?php

/**
 * @group Rule_Indent
 */
class PHP_Formatter_Rule_IndentTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter_Rule_Indent::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Formatter_Rule_Indent();
        $this->assertTrue($rule->getOption('useSpaces'), 'Wrong default Option value for useSpaces');
        $this->assertEquals(4, $rule->getOption('tabWidth'), 'Wrong default Option value for tabWidth');
        $this->assertEquals(4, $rule->getOption('indentionWidth'), 'Wrong default Option value for indentionWidth');
        $this->assertEquals(0, $rule->getOption('initialIndentionWidth'), 'Wrong default Option value for initialIndentionWidth');
    }

    /**
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();
        $path = '/Rule/Indent/';

        #0
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'input1'),
            $this->getTokenArrayFromFixtureFile($path . 'output1'),
        );

        #1
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'input2'),
            $this->getTokenArrayFromFixtureFile($path . 'output2'),
        );

        #2
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'input3'),
            $this->getTokenArrayFromFixtureFile($path . 'output3'),
        );

        #3
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'input4'),
            $this->getTokenArrayFromFixtureFile($path . 'output4'),
        );

        #4
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'input5'),
            $this->getTokenArrayFromFixtureFile($path . 'output5'),
        );

        #5
        $data[] = array(
            array(),
            $this->getTokenArrayFromFixtureFile($path . 'input6'),
            $this->getTokenArrayFromFixtureFile($path . 'output6'),
        );

        #6
        $data[] = array(
            array('useSpaces' => false),
            PHP_Formatter_TokenContainer::createFromCode("<?php\nfunction foo(\$baa) {\necho \$foo;\n}"),
            PHP_Formatter_TokenContainer::createFromCode("<?php\nfunction foo(\$baa) {\n\techo \$foo;\n}")
        );

        return $data;
    }

    /**
     * @covers PHP_Formatter_Rule_Indent
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Formatter_Rule_Indent($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}