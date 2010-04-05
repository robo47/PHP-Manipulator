<?php

/**
 * @group ContainerManipulator_CreateMultilineCommentFromTokenToToken
 */
class PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToTokenTest extends TestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();

        $container = PHP_Manipulator_TokenContainer::createFromCode("<?php \$blub = \$bla; ?>");
        $from = $container[1]; // $blub
        $to = $container[6];   // ;

        # 0
        $data[] = array(
            $container,
            array('from' => $from, 'to' => $to),
            PHP_Manipulator_TokenContainer::createFromCode("<?php /*\$blub = \$bla;*/ ?>"),
            false
        );

        $container = PHP_Manipulator_TokenContainer::createFromCode("<?php \$blub =/* foo */ \$bla; ?>");
        $from = $container[1]; // $blub
        $to = $container[7];   // ;

        # 1
        $data[] = array(
            $container,
            array('from' => $from, 'to' => $to),
            PHP_Manipulator_TokenContainer::createFromCode("<?php /*\$blub = \$bla;*/ ?>"),
            false
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken::<protected>
     */
    public function testManipulate($container, $params, $expectedContainer, $strict)
    {
        $manipulator = new PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        $manipulator->manipulate($container, $params);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }

    /**
     * @covers PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Manipulator_Exception
     */
    public function testNonArrayAsParamsThrowsException()
    {
        $container = PHP_Manipulator_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = null;
        $manipulator = new PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Manipulator_Exception $e) {
            $this->assertEquals('invalid input $params should be an array', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Manipulator_Exception
     */
    public function testMissingFromThrowsException()
    {
        $container = PHP_Manipulator_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('to' => $container[5]);
        $manipulator = new PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Manipulator_Exception $e) {
            $this->assertEquals("key 'from' not found in \$params", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Manipulator_Exception
     */
    public function testMissingToThrowsException()
    {
        $container = PHP_Manipulator_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('from' => $container[1]);
        $manipulator = new PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Manipulator_Exception $e) {
            $this->assertEquals("key 'to' not found in \$params", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Manipulator_Exception
     */
    public function testWrongDatatypeForFromThrowsException()
    {
        $container = PHP_Manipulator_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('to' => $container[5], 'from' => 'foo');
        $manipulator = new PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Manipulator_Exception $e) {
            $this->assertEquals("key 'from' is not instance of PHP_Manipulator_Token", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Manipulator_Exception
     */
    public function testWrongDatatypeForToThrowsException()
    {
        $container = PHP_Manipulator_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('to' => 'foo', 'from' => $container[1]);
        $manipulator = new PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Manipulator_Exception $e) {
            $this->assertEquals("key 'to' is not instance of PHP_Manipulator_Token", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Manipulator_Exception
     */
    public function testFromTokenisBehindToTokenThrowsException()
    {
        $container = PHP_Manipulator_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('to' => $container[1], 'from' => $container[5]);
        $manipulator = new PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Manipulator_Exception $e) {
            $this->assertEquals("startOffset is behind endOffset", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Manipulator_Exception
     */
    public function testToIsNotContainedInTheContainerThrowsException()
    {
        $container = PHP_Manipulator_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('to' => new PHP_Manipulator_Token('('), 'from' => $container[1]);
        $manipulator = new PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Manipulator_Exception $e) {
            $this->assertEquals("element 'to' not found in \$container", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken::manipulate
     * @covers PHP_Manipulator_Exception
     */
    public function testFromIsNotContainedInTheContainerThrowsException()
    {
        $container = PHP_Manipulator_TokenContainer::createFromCode("<?php echo 'hellow world'; ?>");
        $params = array('from' => new PHP_Manipulator_Token('('), 'to' => $container[1]);
        $manipulator = new PHP_Manipulator_ContainerManipulator_CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Manipulator_Exception $e) {
            $this->assertEquals("element 'from' not found in \$container", $e->getMessage(), 'Wrong exception message');
        }
    }
}