<?php

/**
 * @group ContainerManipulator_SetWhitespaceAfterToken
 */
class PHP_Formatter_ContainerManipulator_SetWhitespaceAfterTokenTest extends PHPFormatterTestCase
{
    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();

        $path = '/ContainerManipulator/SetWhitespaceAfterToken/';

        #0
        $data[] = array(
            $inputContainer = $this->getTokenArrayFromFixtureFile($path . 'input0'),
            $this->getTokenArrayFromFixtureFile($path . 'output0'),
            array(
                'tokens' => array($inputContainer[3]),
                'whitespace' => array(T_CONCAT_EQUAL => ' '),
            ),
            false
        );

        #1
        $data[] = array(
            $inputContainer = $this->getTokenArrayFromFixtureFile($path . 'input1'),
            $this->getTokenArrayFromFixtureFile($path . 'output1'),
            array(
                'tokens' => array($inputContainer[3]),
                'whitespace' => array(T_CONCAT_EQUAL => '  '),
            ),
            false
        );

        #2
        $data[] = array(
            $inputContainer = $this->getTokenArrayFromFixtureFile($path . 'input2'),
            $this->getTokenArrayFromFixtureFile($path . 'output2'),
            array(
                'tokens' => array($inputContainer[3]),
                'whitespace' => array(T_CONCAT_EQUAL => ''),
            ),
            false
        );

        #3
        $data[] = array(
            $inputContainer = $this->getTokenArrayFromFixtureFile($path . 'input3'),
            $this->getTokenArrayFromFixtureFile($path . 'output3'),
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
     * @covers PHP_Formatter_ContainerManipulator_SetWhitespaceAfterToken
     */
    public function testManipulate($container, $expectedContainer, $params, $strict)
    {
        $manipulator = new PHP_Formatter_ContainerManipulator_SetWhitespaceAfterToken();
        $manipulator->manipulate($container, $params);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }

    /**
     * @covers PHP_Formatter_ContainerManipulator_SetWhitespaceAfterToken::manipulate
     * @covers PHP_Formatter_Exception
     */
    public function testMissingWhitespaceThrowsException()
    {
        $container = PHP_Formatter_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('tokens' => array($container[5], $container[6]));
        $manipulator = new PHP_Formatter_ContainerManipulator_SetWhitespaceAfterToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("key 'whitespace' not found in \$params", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_ContainerManipulator_SetWhitespaceAfterToken::manipulate
     * @covers PHP_Formatter_Exception
     */
    public function testMissingTokensThrowsException()
    {
        $container = PHP_Formatter_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('whitespace' => array(T_ECHO => 'blub'));
        $manipulator = new PHP_Formatter_ContainerManipulator_SetWhitespaceAfterToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("key 'tokens' not found in \$params", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_ContainerManipulator_SetWhitespaceAfterToken::manipulate
     * @covers PHP_Formatter_Exception
     */
    public function testParamIsNotArrayThrowsException()
    {
        $container = PHP_Formatter_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $manipulator = new PHP_Formatter_ContainerManipulator_SetWhitespaceAfterToken();
        try {
            $manipulator->manipulate($container);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("invalid input \$params should be an array", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_ContainerManipulator_SetWhitespaceAfterToken::getWhitespaceForToken
     * @covers PHP_Formatter_Exception
     */
    public function testNonExistingTokenInWhitespaceListThrowsExceptionInGetWhitespaceForToken()
    {
        $container = PHP_Formatter_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('tokens' => array($container[2], $container[3]), 'whitespace' => array(T_ECHO => 'blub'));
        $manipulator = new PHP_Formatter_ContainerManipulator_SetWhitespaceAfterToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals('No option found for: T_WHITESPACE (371)', $e->getMessage(), 'Wrong exception message');
        }
    }
}