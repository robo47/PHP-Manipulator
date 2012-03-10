<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ChangeLineEndings;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\ChangeLineEndings
 */
class ChangeLineEndingsTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\ChangeLineEndings::init
     */
    public function testConstructorDefaults()
    {
        $action = new ChangeLineEndings();
        $this->assertEquals("\n", $action->getOption('newline'), 'Default Value for newline is wrong');
        $this->assertCount(1, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
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
     * @covers \PHP\Manipulator\Action\ChangeLineEndings::run
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new ChangeLineEndings($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
