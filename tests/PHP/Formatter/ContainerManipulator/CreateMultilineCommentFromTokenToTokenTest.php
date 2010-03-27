<?php

require_once 'PHP/Formatter/ContainerManipulator/CreateMultilineCommentFromTokenToToken.php';

class PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToTokenTest extends PHPFormatterTestCase
{
    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();

        $container = PHP_Formatter_TokenContainer::createFromCode("<?php \$blub = \$bla; ?>");
        $from = $container[1]; // $blub
        $to = $container[6];   // ;

        # 0
        $data[] = array(
            $container,
            array('from' => $from, 'to' => $to),
            PHP_Formatter_TokenContainer::createFromCode("<?php /*\$blub = \$bla;*/ ?>"),
            true,
            false
        );
        
        $container = PHP_Formatter_TokenContainer::createFromCode("<?php \$blub =/* foo */ \$bla; ?>");
        $from = $container[1]; // $blub
        $to = $container[7];   // ;

        # 1
        $data[] = array(
            $container,
            array('from' => $from, 'to' => $to),
            PHP_Formatter_TokenContainer::createFromCode("<?php /*\$blub = \$bla;*/ ?>"),
            true,
            false
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     */
    public function testManipulate($container, $params, $expectedContainer, $changed, $strict)
    {
        $manipulator = new PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        $this->assertSame($changed, $manipulator->manipulate($container, $params), 'Wrong return value');
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }

    /**
     * @covers PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Formatter_Exception
     */
    public function testNonArrayAsParamsThrowsException()
    {
        $container = PHP_Formatter_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = null;
        $manipulator = new PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals('invalid input $params should be an array', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Formatter_Exception
     */
    public function testMissingFromThrowsException()
    {
        $container = PHP_Formatter_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('to' => $container[5]);
        $manipulator = new PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("key 'from' not found in \$params", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Formatter_Exception
     */
    public function testMissingToThrowsException()
    {
        $container = PHP_Formatter_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('from' => $container[1]);
        $manipulator = new PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("key 'to' not found in \$params", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Formatter_Exception
     */
    public function testWrongDatatypeForFromThrowsException()
    {
        $container = PHP_Formatter_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('to' => $container[5], 'from' => 'foo');
        $manipulator = new PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("key 'from' is not instance of PHP_Formatter_Token", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Formatter_Exception
     */
    public function testWrongDatatypeForToThrowsException()
    {
        $container = PHP_Formatter_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('to' => 'foo', 'from' => $container[1]);
        $manipulator = new PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("key 'to' is not instance of PHP_Formatter_Token", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Formatter_Exception
     */
    public function testFromTokenisBehindToTokenThrowsException()
    {
        $container = PHP_Formatter_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('to' => $container[1], 'from' => $container[5]);
        $manipulator = new PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("startOffset is behind endOffset", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Formatter_Exception
     */
    public function testToIsNotContainedInTheContainerThrowsException()
    {
        $container = PHP_Formatter_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('to' => new PHP_Formatter_Token('('), 'from' => $container[1]);
        $manipulator = new PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("element 'to' not found in \$container", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Formatter_Exception
     */
    public function testFromIsNotContainedInTheContainerThrowsException()
    {
        $container = PHP_Formatter_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('from' => new PHP_Formatter_Token('('), 'to' => $container[1]);
        $manipulator = new PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Formatter_Exception $e) {
            $this->assertEquals("element 'from' not found in \$container", $e->getMessage(), 'Wrong exception message');
        }
    }
}