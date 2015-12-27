<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ChangeLineEndings;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\ChangeLineEndings
 */
class ChangeLineEndingsTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new ChangeLineEndings();
        $this->assertSame(
            "\n",
            $action->getOption(ChangeLineEndings::OPTION_NEWLINE),
            'Default Value for newline is wrong'
        );
        $this->assertCount(1, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = [];

        $codeLinux   = "<?php\necho \$foo;\n\n if(true) {\n    echo 'foo';\n} else {\n echo 'baa';\n}";
        $codeWindows = "<?php\r\necho \$foo;\r\n\r\n if(true) {\r\n    echo 'foo';\r\n} else {\r\n echo 'baa';\r\n}";
        $codeMac     = "<?php\recho \$foo;\r\r if(true) {\r    echo 'foo';\r} else {\r echo 'baa';\r}";

        #0
        $data[] = [
            [],
            TokenContainer::factory($codeWindows),
            TokenContainer::factory($codeLinux),
        ];

        #1
        $data[] = [
            [ChangeLineEndings::OPTION_NEWLINE => "\r\n"],
            TokenContainer::factory($codeLinux),
            TokenContainer::factory($codeWindows),
        ];

        #2
        $data[] = [
            [ChangeLineEndings::OPTION_NEWLINE => "\r"],
            TokenContainer::factory($codeLinux),
            TokenContainer::factory($codeMac),
        ];

        #3
        $data[] = [
            [ChangeLineEndings::OPTION_NEWLINE => "\n"],
            TokenContainer::factory($codeMac),
            TokenContainer::factory($codeLinux),
        ];

        #4
        $data[] = [
            [ChangeLineEndings::OPTION_NEWLINE => "\r\n"],
            TokenContainer::factory($codeMac),
            TokenContainer::factory($codeWindows),
        ];

        #5
        $data[] = [
            [ChangeLineEndings::OPTION_NEWLINE => "\r"],
            TokenContainer::factory($codeWindows),
            TokenContainer::factory($codeMac),
        ];

        return $data;
    }

    /**
     * @dataProvider actionProvider
     *
     * @param array          $options
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     */
    public function testAction(array $options, TokenContainer $input, TokenContainer $expectedTokens)
    {
        $action = new ChangeLineEndings($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
