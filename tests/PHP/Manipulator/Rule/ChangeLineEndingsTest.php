<?php

namespace Tests\PHP\Manipulator\Rule;

use PHP\Manipulator\Rule\ChangeLineEndings;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Rule_ChangeLineEndings
 */
class ChangeLineEndingsTest extends \Tests\TestCase
{

    /**
     * @covers PHP\Manipulator\Rule\ChangeLineEndings::init
     */
    public function testConstructorDefaults()
    {
        $rule = new ChangeLineEndings();
        $this->assertEquals("\n", $rule->getOption('newline'), 'Wrong default Option value for newline');
    }

    /**
     *
     * @return array
     */
    public function ruleProvider()
    {
        $data = array();

        $codeLinux = "<?php\necho \$foo;\n\n if(true) {\n    echo 'foo';\n} else {\n echo 'baa';\n}";
        $codeWindows = "<?php\r\necho \$foo;\r\n\r\n if(true) {\r\n    echo 'foo';\r\n} else {\r\n echo 'baa';\r\n}";
        $codeMac = "<?php\recho \$foo;\r\r if(true) {\r    echo 'foo';\r} else {\r echo 'baa';\r}";

        #0
        $data[] = array(
            array(),
            new TokenContainer($codeWindows),
            new TokenContainer($codeLinux),
        );

        #1
        $data[] = array(
            array('newline' => "\r\n"),
            new TokenContainer($codeLinux),
            new TokenContainer($codeWindows),
        );

        #2
        $data[] = array(
            array('newline' => "\r"),
            new TokenContainer($codeLinux),
            new TokenContainer($codeMac),
        );

        #3
        $data[] = array(
            array('newline' => "\n"),
            new TokenContainer($codeMac),
            new TokenContainer($codeLinux),
        );

        #4
        $data[] = array(
            array('newline' => "\r\n"),
            new TokenContainer($codeMac),
            new TokenContainer($codeWindows),
        );

        #5
        $data[] = array(
            array('newline' => "\r"),
            new TokenContainer($codeWindows),
            new TokenContainer($codeMac),
        );

        return $data;
    }

    /**
     *
     * @covers PHP\Manipulator\Rule\ChangeLineEndings::applyRuleToTokens
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new ChangeLineEndings($options);
        $rule->applyRuleToTokens($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}