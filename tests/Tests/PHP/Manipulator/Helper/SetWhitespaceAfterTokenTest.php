<?php

namespace Tests\PHP\Manipulator\Helper;

use PHP\Manipulator\Exception\HelperException;
use PHP\Manipulator\Helper\SetWhitespaceAfterToken;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Helper\SetWhitespaceAfterToken
 */
class SetWhitespaceAfterTokenTest extends TestCase
{
    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = [];

        $path = '/Helper/SetWhitespaceAfterToken/';

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
            [$inputContainer[3]],
            [T_CONCAT_EQUAL => '  '],
        ];

        #2
        $data[] = [
            $inputContainer = $this->getContainerFromFixture($path.'input2.php'),
            $this->getContainerFromFixture($path.'output2.php'),
            [$inputContainer[3]],
            [T_CONCAT_EQUAL => ''],
        ];

        #3
        $data[] = [
            $inputContainer = $this->getContainerFromFixture($path.'input3.php'),
            $this->getContainerFromFixture($path.'output3.php'),
            [$inputContainer[3]],
            ['=' => ' '],
        ];

        return $data;
    }

    /**
     * @param TokenContainer $container
     * @param TokenContainer $expectedContainer
     * @param array          $tokens
     * @param array          $whitespace
     *
     * @dataProvider manipulateProvider
     */
    public function testManipulate($container, $expectedContainer, $tokens, $whitespace)
    {
        $manipulator = new SetWhitespaceAfterToken();
        $manipulator->run($container, $tokens, $whitespace);
        $this->assertTokenContainerMatch($expectedContainer, $container, false);
    }

    public function testNonExistingTokenInWhitespaceListThrowsExceptionInGetWhitespaceForToken()
    {
        $container   = TokenContainer::factory("<?php echo 'hellow world'; ?>");
        $tokens      = [$container[2], $container[3]];
        $whitespace  = [T_ECHO => 'blub'];
        $manipulator = new SetWhitespaceAfterToken();

        $this->setExpectedException(HelperException::class, '', HelperException::OPTION_NOT_FOUND);

        $manipulator->run($container, $tokens, $whitespace);
    }
}
