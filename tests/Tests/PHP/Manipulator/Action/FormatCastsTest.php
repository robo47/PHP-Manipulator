<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\FormatCasts;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\FormatCasts
 */
class FormatCastsTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $searchedTokens = [
            T_INT_CAST    => '(int)',
            T_BOOL_CAST   => '(bool)',
            T_DOUBLE_CAST => '(double)',
            T_OBJECT_CAST => '(object)',
            T_STRING_CAST => '(string)',
            T_UNSET_CAST  => '(unset)',
            T_ARRAY_CAST  => '(array)',
        ];
        $action = new FormatCasts();
        $this->assertSame($searchedTokens, $action->getOption(FormatCasts::OPTION_SEARCHED_TOKENS));
        $this->assertSame('', $action->getOption(FormatCasts::OPTION_WHITESPACE_BEHIND_CASTS));
        $this->assertCount(2, $action->getOptions());
    }

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = [];
        $path = '/Action/FormatCasts/';

        $data['Replace all tokens'] = [
            $this->getContainerFromFixture($path.'input0.php'),
            $this->getContainerFromFixture($path.'output0.php'),
            [
                FormatCasts::OPTION_SEARCHED_TOKENS => [
                    T_INT_CAST    => '(iNt)',
                    T_BOOL_CAST   => '(bOoL)',
                    T_DOUBLE_CAST => '(dOuBlE)',
                    T_OBJECT_CAST => '(oBjEcT)',
                    T_STRING_CAST => '(sTrInG)',
                    T_UNSET_CAST  => '(uNsEt)',
                    T_ARRAY_CAST  => '(aRrAy)',
                ],
            ],
            true,
        ];

        $data['Uppercase to lowercase'] = [
            $this->getContainerFromFixture($path.'input1.php'),
            $this->getContainerFromFixture($path.'output1.php'),
            [],
            true,
        ];

        $data['Test whitespace is created if wanted'] = [
            $this->getContainerFromFixture($path.'input2.php'),
            $this->getContainerFromFixture($path.'output2.php'),
            [FormatCasts::OPTION_WHITESPACE_BEHIND_CASTS => ' '],
            false,
        ];

        $data['Test whitespace gets removed if not wanted'] = [
            $this->getContainerFromFixture($path.'input3.php'),
            $this->getContainerFromFixture($path.'output3.php'),
            [FormatCasts::OPTION_WHITESPACE_BEHIND_CASTS => ''],
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     *
     * @param TokenContainer $container
     * @param TokenContainer $expectedContainer
     * @param array          $options
     * @param bool           $strict
     */
    public function testManipulate(
        TokenContainer $container,
        TokenContainer $expectedContainer,
        array $options,
        $strict
    ) {
        $manipulator = new FormatCasts($options);
        $manipulator->run($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}
