<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\Indent;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @todo   more tests with tabs instead of spaces
 * @todo   test initial Indention
 * @todo   test indentionWidth
 * @todo   test tabWidth
 * @todo   test indention works if we place the curly braces on the following line
 * @todo   CurlyBrace-Indention ? indenting curly-braces with an extra value (2 spaces ... ? )
 * @todo   make multi-line calls and array-assignments work
 * @covers PHP\Manipulator\Action\Indent
 */
class IndentTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new Indent();
        $this->assertTrue(
            $action->getOption(Indent::OPTION_USE_SPACES),
            'Default value for useSpaces is wrong'
        );
        $this->assertSame(
            4,
            $action->getOption(Indent::OPTION_TAB_WIDTH),
            'Default value for tabWidth is wrong'
        );
        $this->assertSame(
            4,
            $action->getOption(Indent::OPTION_INDENTION_WIDTH),
            'Default value for indentionWidth is wrong'
        );
        $this->assertSame(
            0,
            $action->getOption(Indent::OPTION_INITIAL_INDENTION_WIDTH),
            'Default value for initialIndentionWidth is wrong'
        );
        $this->assertCount(4, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = [];
        $path = '/Action/Indent/';

        #0
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input0.php'),
            $this->getContainerFromFixture($path.'output0.php'),
        ];

        #1
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input1.php'),
            $this->getContainerFromFixture($path.'output1.php'),
        ];

        #2
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input2.php'),
            $this->getContainerFromFixture($path.'output2.php'),
        ];

        #3
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input3.php'),
            $this->getContainerFromFixture($path.'output3.php'),
        ];

        #4
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input4.php'),
            $this->getContainerFromFixture($path.'output4.php'),
        ];

        #5
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input5.php'),
            $this->getContainerFromFixture($path.'output5.php'),
        ];

        #6 Empty code to test notething goes wrong with it :P
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input6.php'),
            $this->getContainerFromFixture($path.'output6.php'),
        ];

        #7
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input7.php'),
            $this->getContainerFromFixture($path.'output7.php'),
        ];

        #8 switch 1
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input8.php'),
            $this->getContainerFromFixture($path.'output8.php'),
        ];

        #9 switch 2 case directly followed by case
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input9.php'),
            $this->getContainerFromFixture($path.'output9.php'),
        ];

        #10 static multi-line function call
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input10.php'),
            $this->getContainerFromFixture($path.'output10.php'),
        ];

        #11 non-static multi-line function call
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input11.php'),
            $this->getContainerFromFixture($path.'output11.php'),
        ];

        #12 multi-line array
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input12.php'),
            $this->getContainerFromFixture($path.'output12.php'),
        ];

        #13 multi-line array in multi-line-function call
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input13.php'),
            $this->getContainerFromFixture($path.'output13.php'),
        ];

        #14 multi-line use
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input14.php'),
            $this->getContainerFromFixture($path.'output14.php'),
        ];

        #15 multi-line use inside namespace-declaration
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input15.php'),
            $this->getContainerFromFixture($path.'output15.php'),
        ];

        #16 Indention after {
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input16.php'),
            $this->getContainerFromFixture($path.'output16.php'),
        ];

        #17 Bugfix Indention after comment
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input17.php'),
            $this->getContainerFromFixture($path.'output17.php'),
        ];

        #18 [BUG] Whitespace should not be prefixed with another whitespace
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input18.php'),
            $this->getContainerFromFixture($path.'output18.php'),
        ];

        #19 wrong indention for default
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input19.php'),
            $this->getContainerFromFixture($path.'output19.php'),
        ];

        #20 wrong indention for default or break at the end without break
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input20.php'),
            $this->getContainerFromFixture($path.'output20.php'),
        ];

        #21 switch with if in case
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input21.php'),
            $this->getContainerFromFixture($path.'output21.php'),
        ];

        #22 Curly Braces inside strings
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input22.php'),
            $this->getContainerFromFixture($path.'output22.php'),
        ];

        #23 if / else / elseif / endif (Alternate Syntax)
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input23.php'),
            $this->getContainerFromFixture($path.'output23.php'),
        ];

        #24 Test with Tab
        $data[] = [
            [Indent::OPTION_USE_SPACES => false],
            TokenContainer::factory("<?php\nfunction foo(\$baa) {\necho \$foo;\n}"),
            TokenContainer::factory("<?php\nfunction foo(\$baa) {\n\techo \$foo;\n}"),
        ];

        return $data;
    }

    /**
     * @param array          $options
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     *
     * @dataProvider actionProvider
     */
    public function testAction(array $options, TokenContainer $input, TokenContainer $expectedTokens)
    {
        $action = new Indent($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
