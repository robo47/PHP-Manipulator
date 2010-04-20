<?php

namespace Tests\PHP\Manipulator\Rule;

use PHP\Manipulator\Rule\Indent;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Rule
 * @group Rule\Indent
 */
class IndentTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Rule\Indent::init
     */
    public function testConstructorDefaults()
    {
        $rule = new Indent();
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
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
        );

        #1
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        #2
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
        );

        #3
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input3'),
            $this->getContainerFromFixture($path . 'output3'),
        );

        #4
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input4'),
            $this->getContainerFromFixture($path . 'output4'),
        );

        #5
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input5'),
            $this->getContainerFromFixture($path . 'output5'),
        );

        #6
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input6'),
            $this->getContainerFromFixture($path . 'output6'),
        );

        #7
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input7'),
            $this->getContainerFromFixture($path . 'output7'),
        );

        #8 switch 1
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input8'),
            $this->getContainerFromFixture($path . 'output8'),
        );

        #9 switch 2 case directly followed by case
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input9'),
            $this->getContainerFromFixture($path . 'output9'),
        );

        #10 switch case without break
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input9'),
            $this->getContainerFromFixture($path . 'output9'),
        );

        #11 switch case without break
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input10'),
            $this->getContainerFromFixture($path . 'output10'),
        );

        #11
        $data[] = array(
            array('useSpaces' => false),
            new TokenContainer("<?php\nfunction foo(\$baa) {\necho \$foo;\n}"),
            new TokenContainer("<?php\nfunction foo(\$baa) {\n\techo \$foo;\n}")
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Rule\Indent
     * @dataProvider ruleProvider
     */
    public function testRule($options, $input, $expectedTokens)
    {
        $rule = new Indent($options);
        $rule->apply($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}