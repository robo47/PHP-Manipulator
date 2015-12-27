<?php

namespace Tests\PHP\Manipulator\Helper;

use PHP\Manipulator\Helper\SetWhitespaceBeforeToken;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Helper\SetWhitespaceBeforeToken
 */
class SetWhitespaceBeforeTokenTest extends TestCase
{
    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = [];
        $path = '/Helper/SetWhitespaceBeforeToken/';

        #0
        $data[] = [
            $inputContainer = $this->getContainerFromFixture($path.'input0.php'),
            $this->getContainerFromFixture($path.'output0.php'),
            [$inputContainer[3]],
            [T_CONCAT_EQUAL => ' '],
        ];

        #1
        $data[] = [
            $inputContainer = $this->getContainerFromFixture($path.'input1.php'),
            $this->getContainerFromFixture($path.'output1.php'),
            [$inputContainer[4]],
            [T_CONCAT_EQUAL => '  '],
        ];

        #2
        $data[] = [
            $inputContainer = $this->getContainerFromFixture($path.'input2.php'),
            $this->getContainerFromFixture($path.'output2.php'),
            [$inputContainer[4]],
            [T_CONCAT_EQUAL => ''],
        ];

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     *
     * @param TokenContainer $container
     * @param TokenContainer $expectedContainer
     * @param array          $tokens
     * @param array          $whitespace
     */
    public function testManipulate(
        TokenContainer $container,
        TokenContainer $expectedContainer,
        $tokens,
        $whitespace
    ) {
        $manipulator = new SetWhitespaceBeforeToken();
        $manipulator->run($container, $tokens, $whitespace);
        $this->assertTokenContainerMatch($expectedContainer, $container, false);
    }
}
