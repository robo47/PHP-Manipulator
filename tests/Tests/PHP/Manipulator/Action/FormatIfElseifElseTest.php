<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\FormatIfElseifElse;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\FormatIfElseifElse
 */
class FormatIfElseifElseTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new FormatIfElseifElse();
        $this->assertTrue(
            $action->getOption(FormatIfElseifElse::OPTION_SPACE_AFTER_IF),
            'Default value for spaceAfterIf is wrong'
        );
        $this->assertTrue(
            $action->getOption(FormatIfElseifElse::OPTION_SPACE_AFTER_ELSEIF),
            'Default value for spaceAfterElseif is wrong'
        );
        $this->assertTrue(
            $action->getOption(FormatIfElseifElse::OPTION_SPACE_AFTER_ELSE),
            'Default value for spaceAfterElse is wrong'
        );

        $this->assertTrue(
            $action->getOption(FormatIfElseifElse::OPTION_SPACE_BEFORE_ELSEIF),
            'Default value for spaceBeforeElseif is wrong'
        );
        $this->assertTrue(
            $action->getOption(FormatIfElseifElse::OPTION_SPACE_BEFORE_ELSE),
            'Default value for spaceBeforeElse is wrong'
        );

        $this->assertTrue(
            $action->getOption(FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF),
            'Default value for breakAfterCurlyBraceOfIf is wrong'
        );
        $this->assertTrue(
            $action->getOption(FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE),
            'Default value for breakAfterCurlyBraceOfElse is wrong'
        );
        $this->assertTrue(
            $action->getOption(FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF),
            'Default value for breakAfterCurlyBraceOfElseif is wrong'
        );

        $this->assertTrue(
            $action->getOption(FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE),
            'Default value for breakBeforeCurlyBraceOfElse is wrong'
        );
        $this->assertTrue(
            $action->getOption(FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF),
            'Default value for breakBeforeCurlyBraceOfElseif is wrong'
        );

        $this->assertFalse(
            $action->getOption(FormatIfElseifElse::OPTION_BREAK_AFTER_IF),
            'Default value for breakAfterIf is wrong'
        );
        $this->assertFalse(
            $action->getOption(FormatIfElseifElse::OPTION_BREAK_AFTER_ELSE),
            'Default value for breakAfterElse is wrong'
        );
        $this->assertFalse(
            $action->getOption(FormatIfElseifElse::OPTION_BREAK_AFTER_ELSEIF),
            'Default value for breakAfterElseif is wrong'
        );

        $this->assertFalse(
            $action->getOption(FormatIfElseifElse::OPTION_BREAK_BEFORE_ELSE),
            'Default value for breakBeforeElse is wrong'
        );
        $this->assertFalse(
            $action->getOption(FormatIfElseifElse::OPTION_BREAK_BEFORE_ELSEIF),
            'Default value for breakBeforeElseif is wrong'
        );

        $this->assertSame(
            '',
            $action->getOption(FormatIfElseifElse::OPTION_SPACE_BEFORE_IF_EXPRESSION),
            'Default value for spaceBeforeIfExpression is wrong'
        );
        $this->assertSame(
            '',
            $action->getOption(FormatIfElseifElse::OPTION_SPACE_AFTER_IF_EXPRESSION),
            'Default value for spaceAfterIfExpression is wrong'
        );

        $this->assertSame(
            '',
            $action->getOption(FormatIfElseifElse::OPTION_SPACE_BEFORE_ELSEIF_EXPRESSION),
            'Default value for spaceBeforeElseifExpression is wrong'
        );
        $this->assertSame(
            '',
            $action->getOption(FormatIfElseifElse::OPTION_SPACE_AFTER_ELSEIF_EXPRESSION),
            'Default value for spaceAfterElseifExpression is wrong'
        );

        $this->assertCount(19, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = [];
        $path = '/Action/FormatIfElseifElse/';

        $data['Test space after if is inserted (input|output)0.php'] = [
            [
                FormatIfElseifElse::OPTION_SPACE_AFTER_IF                     => true,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF      => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE    => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF  => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE   => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF => false,
            ],
            $this->getContainerFromFixture($path.'input0.php'),
            $this->getContainerFromFixture($path.'output0.php'),
        ];

        $data['Test if there is whitespace after if (input|output)1.php'] = [
            [
                FormatIfElseifElse::OPTION_SPACE_AFTER_IF                     => true,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF      => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE    => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF  => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE   => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF => false,
            ],
            $this->getContainerFromFixture($path.'input1.php'),
            $this->getContainerFromFixture($path.'output1.php'),
        ];

        $data['Test if there is whitespace after if (input|output)2.php'] = [
            [
                FormatIfElseifElse::OPTION_SPACE_AFTER_IF                     => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF      => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE    => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF  => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE   => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF => false,
            ],
            $this->getContainerFromFixture($path.'input2.php'),
            $this->getContainerFromFixture($path.'output2.php'),
        ];

        $data['Test SpaceAfter And BeforeElse without existing space (input|output)3.php'] = [
            [
                FormatIfElseifElse::OPTION_SPACE_AFTER_ELSE                   => true,
                FormatIfElseifElse::OPTION_SPACE_BEFORE_ELSE                  => true,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF      => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE    => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF  => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE   => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF => false,
            ],
            $this->getContainerFromFixture($path.'input3.php'),
            $this->getContainerFromFixture($path.'output3.php'),
        ];

        $data['Test true SpaceAfter And BeforeElse with too much existing space (input|output)4.php'] = [
            [
                FormatIfElseifElse::OPTION_SPACE_AFTER_ELSE                   => true,
                FormatIfElseifElse::OPTION_SPACE_BEFORE_ELSE                  => true,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF      => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE    => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF  => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE   => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF => false,
            ],
            $this->getContainerFromFixture($path.'input4.php'),
            $this->getContainerFromFixture($path.'output4.php'),
        ];

        $data['Test false SpaceAfter And BeforeElse with existing space (input|output)5.php'] = [
            [
                FormatIfElseifElse::OPTION_SPACE_AFTER_ELSE                   => false,
                FormatIfElseifElse::OPTION_SPACE_BEFORE_ELSE                  => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF      => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE    => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF  => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE   => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF => false,
            ],
            $this->getContainerFromFixture($path.'input5.php'),
            $this->getContainerFromFixture($path.'output5.php'),
        ];

        #6
        $data['Test SpaceAfter And BeforeElse without existing space (input|output)6.php'] = [
            [
                FormatIfElseifElse::OPTION_SPACE_AFTER_ELSEIF                 => true,
                FormatIfElseifElse::OPTION_SPACE_BEFORE_ELSEIF                => true,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF      => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE    => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF  => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE   => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF => false,
            ],
            $this->getContainerFromFixture($path.'input6.php'),
            $this->getContainerFromFixture($path.'output6.php'),
        ];

        $data['Test true SpaceAfter And BeforeElse with too much existing space (input|output)7.php'] = [
            [
                FormatIfElseifElse::OPTION_SPACE_AFTER_ELSEIF                 => true,
                FormatIfElseifElse::OPTION_SPACE_BEFORE_ELSEIF                => true,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF      => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE    => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF  => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE   => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF => false,
            ],
            $this->getContainerFromFixture($path.'input7.php'),
            $this->getContainerFromFixture($path.'output7.php'),
        ];

        $data['Test false SpaceAfter And BeforeElse with existing space (input|output)8.php'] = [
            [
                FormatIfElseifElse::OPTION_SPACE_AFTER_ELSEIF                 => false,
                FormatIfElseifElse::OPTION_SPACE_BEFORE_ELSEIF                => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF      => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE    => false,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF  => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE   => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF => false,
            ],
            $this->getContainerFromFixture($path.'input8.php'),
            $this->getContainerFromFixture($path.'output8.php'),
        ];

        $data['Test breaking works (input|output)9.php'] = [
            [
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF      => true,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE    => true,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF  => true,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE   => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF => false,
            ],
            $this->getContainerFromFixture($path.'input9.php'),
            $this->getContainerFromFixture($path.'output9.php'),
        ];

        #10
        $data['Test breaking works with nested if (input|output)10.php'] = [
            [
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF     => true,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE   => true,
                FormatIfElseifElse::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF => true,
                'breakBeforeCurlyBraceOfElseAndElseif'                       => true,
            ],
            $this->getContainerFromFixture($path.'input10.php'),
            $this->getContainerFromFixture($path.'output10.php'),
        ];

        $data['Test breaking works (input|output)11.php'] = [
            [],
            $this->getContainerFromFixture($path.'input11.php'),
            $this->getContainerFromFixture($path.'output11.php'),
        ];

        $data['Test breaks after/before if/else/elseif (input|output)12.php'] = [
            [
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE   => true,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF => true,
                FormatIfElseifElse::OPTION_BREAK_AFTER_IF                     => true,
                FormatIfElseifElse::OPTION_BREAK_AFTER_ELSE                   => true,
                FormatIfElseifElse::OPTION_BREAK_AFTER_ELSEIF                 => true,
                FormatIfElseifElse::OPTION_SPACE_BEFORE_ELSEIF                => false,
                FormatIfElseifElse::OPTION_SPACE_BEFORE_ELSE                  => false,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_ELSE                  => true,
                FormatIfElseifElse::OPTION_BREAK_BEFORE_ELSEIF                => true,
                FormatIfElseifElse::OPTION_SPACE_AFTER_ELSE                   => false,
            ],
            $this->getContainerFromFixture($path.'input12.php'),
            $this->getContainerFromFixture($path.'output12.php'),
        ];

        #13
        $data['Spaces for Expressions (input|output)13.php'] = [
            [
                FormatIfElseifElse::OPTION_SPACE_BEFORE_IF_EXPRESSION     => ' ',
                FormatIfElseifElse::OPTION_SPACE_BEFORE_ELSEIF_EXPRESSION => ' ',
                FormatIfElseifElse::OPTION_SPACE_AFTER_IF_EXPRESSION      => ' ',
                FormatIfElseifElse::OPTION_SPACE_AFTER_ELSEIF_EXPRESSION  => ' ',
            ],
            $this->getContainerFromFixture($path.'input13.php'),
            $this->getContainerFromFixture($path.'output13.php'),
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
        $action = new FormatIfElseifElse($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
