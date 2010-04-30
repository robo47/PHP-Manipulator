<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\Indent;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\Indent
 */
class IndentTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\Indent::init
     */
    public function testConstructorDefaults()
    {
        $action = new Indent();
        $this->assertTrue($action->getOption('useSpaces'), 'Wrong default Option value for useSpaces');
        $this->assertEquals(4, $action->getOption('tabWidth'), 'Wrong default Option value for tabWidth');
        $this->assertEquals(4, $action->getOption('indentionWidth'), 'Wrong default Option value for indentionWidth');
        $this->assertEquals(0, $action->getOption('initialIndentionWidth'), 'Wrong default Option value for initialIndentionWidth');
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/Indent/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        #1
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
        );

        #2
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
        );

        #3
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input3.php'),
            $this->getContainerFromFixture($path . 'output3.php'),
        );

        #4
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input4.php'),
            $this->getContainerFromFixture($path . 'output4.php'),
        );

        #5
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input5.php'),
            $this->getContainerFromFixture($path . 'output5.php'),
        );

        #6 Empty code to test notething goes wrong with it :P
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input6.php'),
            $this->getContainerFromFixture($path . 'output6.php'),
        );

        #7
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input7.php'),
            $this->getContainerFromFixture($path . 'output7.php'),
        );

        #8 switch 1
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input8.php'),
            $this->getContainerFromFixture($path . 'output8.php'),
        );

        #9 switch 2 case directly followed by case
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input9.php'),
            $this->getContainerFromFixture($path . 'output9.php'),
        );

        #10 static multi-line function call
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input10.php'),
            $this->getContainerFromFixture($path . 'output10.php'),
        );

        #11 non-static multi-line function call
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input11.php'),
            $this->getContainerFromFixture($path . 'output11.php'),
        );

        #12 multi-line array
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input12.php'),
            $this->getContainerFromFixture($path . 'output12.php'),
        );

        #13 multi-line array in multi-line-function call
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input13.php'),
            $this->getContainerFromFixture($path . 'output13.php'),
        );

        #14 multi-line use
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input14.php'),
            $this->getContainerFromFixture($path . 'output14.php'),
        );

        #15 multi-line use inside namespace-declaration
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input15.php'),
            $this->getContainerFromFixture($path . 'output15.php'),
        );

        #16 Indention after {
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input16.php'),
            $this->getContainerFromFixture($path . 'output16.php'),
        );

        #17 Bugfix Indention after comment
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input17.php'),
            $this->getContainerFromFixture($path . 'output17.php'),
        );

        #18 [BUG] Whitespace should not be prefixed with another whitespace
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input18.php'),
            $this->getContainerFromFixture($path . 'output18.php'),
        );


        #19 wrong indention for default
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input19.php'),
            $this->getContainerFromFixture($path . 'output19.php'),
        );

        #20 wrong indention for default or break at the end without break
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input20.php'),
            $this->getContainerFromFixture($path . 'output20.php'),
        );

        #21 switch with if in case
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input21.php'),
            $this->getContainerFromFixture($path . 'output21.php'),
        );

        #22 Curly Braces inside strings
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input22.php'),
            $this->getContainerFromFixture($path . 'output22.php'),
        );

//@todo nested switch
//        #21 nested switch
//        $data[] = array(
//            array(),
//            $this->getContainerFromFixture($path . 'inputx.php'),
//            $this->getContainerFromFixture($path . 'outputx.php'),
//        );

        // @todo more tests with tabs instead of spaces
        #19 Test with Tab
        $data[] = array(
            array('useSpaces' => false),
            new TokenContainer("<?php\nfunction foo(\$baa) {\necho \$foo;\n}"),
            new TokenContainer("<?php\nfunction foo(\$baa) {\n\techo \$foo;\n}")
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\Indent
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new Indent($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}