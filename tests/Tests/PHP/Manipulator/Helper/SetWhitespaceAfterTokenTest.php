<?php

namespace Tests\PHP\Manipulator\Helper;

use PHP\Manipulator\Helper\SetWhitespaceAfterToken;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Helper
 * @group Helper\SetWhitespaceAfterToken
 */
class SetWhitespaceAfterTokenTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();

        $path = '/Helper/SetWhitespaceAfterToken/';

        #0
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
            array($inputContainer[3]),
            array(T_CONCAT_EQUAL => ' '),
            false
        );

        #1
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
            array($inputContainer[3]),
            array(T_CONCAT_EQUAL => '  '),
            false
        );

        #2
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
            array($inputContainer[3]),
            array(T_CONCAT_EQUAL => ''),
            false
        );

        #3
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input3.php'),
            $this->getContainerFromFixture($path . 'output3.php'),
            array($inputContainer[3]),
            array('=' => ' '),
            false
        );

        return $data;
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param \\PHP\Manipulator\TokenContainer $expectedContainer
     * @param array $tokens
     * @param array $whitespace
     * @param boolean $strict
     *
     * @dataProvider manipulateProvider
     * @covers \PHP\Manipulator\Helper\SetWhitespaceAfterToken
     */
     
    public function testManipulate($container, $expectedContainer, $tokens, $whitespace, $strict)
    {
        $manipulator = new SetWhitespaceAfterToken();
        $manipulator->run($container, $tokens, $whitespace);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }

    /**
     * @covers \PHP\Manipulator\Helper\SetWhitespaceAfterToken::getWhitespaceForToken
     * @covers \Exception
     */
    public function testNonExistingTokenInWhitespaceListThrowsExceptionInGetWhitespaceForToken()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $tokens = array($container[2], $container[3]);
        $whitespace = array(T_ECHO => 'blub');
        $manipulator = new SetWhitespaceAfterToken();
        try {
            $manipulator->run($container, $tokens, $whitespace);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('No option found for: T_WHITESPACE (' . T_WHITESPACE . ')', $e->getMessage(), 'Wrong exception message');
        }
    }
}