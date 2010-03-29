<?php

class PHP_Formatter_Rule_ChangeLineEndingsTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter_Rule_ChangeLineEndings::init
     */
    public function testConstructorDefaults()
    {
        $rule = new PHP_Formatter_Rule_ChangeLineEndings();
        $this->assertEquals("\n", $rule->getOption('newline'), 'Wrong default Option value for newline');
    }

    public function ruleProvider()
    {
        $data = array();

        $codeLinux = "<?php\necho \$foo;\n\n if(true) {\n    echo 'foo';\n} else {\n echo 'baa';\n}";
        $codeWindows = "<?php\r\necho \$foo;\r\n\r\n if(true) {\r\n    echo 'foo';\r\n} else {\r\n echo 'baa';\r\n}";
        $codeMac = "<?php\recho \$foo;\r\r if(true) {\r    echo 'foo';\r} else {\r echo 'baa';\r}";

        #0
        $data[] = array(
            array(),
            PHP_Formatter_TokenContainer::createFromCode($codeWindows),
            PHP_Formatter_TokenContainer::createFromCode($codeLinux),
        );

        #1
        $data[] = array(
            array('newline' => "\r\n"),
            PHP_Formatter_TokenContainer::createFromCode($codeLinux),
            PHP_Formatter_TokenContainer::createFromCode($codeWindows),
        );

        #2
        $data[] = array(
            array('newline' => "\r"),
            PHP_Formatter_TokenContainer::createFromCode($codeLinux),
            PHP_Formatter_TokenContainer::createFromCode($codeMac),
        );

        #3
        $data[] = array(
            array('newline' => "\n"),
            PHP_Formatter_TokenContainer::createFromCode($codeMac),
            PHP_Formatter_TokenContainer::createFromCode($codeLinux),
        );

        #4
        $data[] = array(
            array('newline' => "\r\n"),
            PHP_Formatter_TokenContainer::createFromCode($codeMac),
            PHP_Formatter_TokenContainer::createFromCode($codeWindows),
        );

        #5
        $data[] = array(
            array('newline' => "\r"),
            PHP_Formatter_TokenContainer::createFromCode($codeWindows),
            PHP_Formatter_TokenContainer::createFromCode($codeMac),
        );
        
        return $data;
    }

    /**
     *
     * @covers PHP_Formatter_Rule_ChangeLineEndings::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new PHP_Formatter_Rule_ChangeLineEndings($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}