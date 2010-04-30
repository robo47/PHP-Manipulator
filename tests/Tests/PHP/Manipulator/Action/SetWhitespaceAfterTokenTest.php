<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\SetWhitespaceAfterToken;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\SetWhitespaceAfterToken
 */
class SetWhitespaceAfterTokenTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();

        $path = '/Action/SetWhitespaceAfterToken/';

        #0
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
            array(
                'tokens' => array($inputContainer[3]),
                'whitespace' => array(T_CONCAT_EQUAL => ' '),
            ),
            false
        );

        #1
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
            array(
                'tokens' => array($inputContainer[3]),
                'whitespace' => array(T_CONCAT_EQUAL => '  '),
            ),
            false
        );

        #2
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
            array(
                'tokens' => array($inputContainer[3]),
                'whitespace' => array(T_CONCAT_EQUAL => ''),
            ),
            false
        );

        #3
        $data[] = array(
            $inputContainer = $this->getContainerFromFixture($path . 'input3'),
            $this->getContainerFromFixture($path . 'output3'),
            array(
                'tokens' => array($inputContainer[3]),
                'whitespace' => array('=' => ' '),
            ),
            false
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers \PHP\Manipulator\Action\SetWhitespaceAfterToken
     */
    public function testManipulate($container, $expectedContainer, $params, $strict)
    {
        $manipulator = new SetWhitespaceAfterToken();
        $manipulator->run($container, $params);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }

    /**
     * @covers \PHP\Manipulator\Action\SetWhitespaceAfterToken::manipulate
     * @covers \Exception
     */
    public function testMissingWhitespaceThrowsException()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $params = array('tokens' => array($container[5], $container[6]));
        $manipulator = new SetWhitespaceAfterToken();
        try {
            $manipulator->run($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("key 'whitespace' not found in \$params", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\Action\SetWhitespaceAfterToken::manipulate
     * @covers \Exception
     */
    public function testMissingTokensThrowsException()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $params = array('whitespace' => array(T_ECHO => 'blub'));
        $manipulator = new SetWhitespaceAfterToken();
        try {
            $manipulator->run($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("key 'tokens' not found in \$params", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\Action\SetWhitespaceAfterToken::manipulate
     * @covers \Exception
     */
    public function testParamIsNotArrayThrowsException()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $manipulator = new SetWhitespaceAfterToken();
        try {
            $manipulator->run($container);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('invalid input $params should be an array', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\Action\SetWhitespaceAfterToken::getWhitespaceForToken
     * @covers \Exception
     */
    public function testNonExistingTokenInWhitespaceListThrowsExceptionInGetWhitespaceForToken()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $params = array('tokens' => array($container[2], $container[3]), 'whitespace' => array(T_ECHO => 'blub'));
        $manipulator = new SetWhitespaceAfterToken();
        try {
            $manipulator->run($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('No option found for: T_WHITESPACE (' . T_WHITESPACE . ')', $e->getMessage(), 'Wrong exception message');
        }
    }
}