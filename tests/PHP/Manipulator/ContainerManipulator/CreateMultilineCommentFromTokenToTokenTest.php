<?php

namespace Tests\PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator\CreateMultilineCommentFromTokenToToken;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group ContainerManipulator_CreateMultilineCommentFromTokenToToken
 */
class CreateMultilineCommentFromTokenToTokenTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();

        $container = new TokenContainer("<?php \$blub = \$bla; ?>");
        $from = $container[1]; // $blub
        $to = $container[6];   // ;

        # 0
        $data[] = array(
            $container,
            array('from' => $from, 'to' => $to),
            new TokenContainer("<?php /*\$blub = \$bla;*/ ?>"),
            false
        );

        $container = new TokenContainer("<?php \$blub =/* foo */ \$bla; ?>");
        $from = $container[1]; // $blub
        $to = $container[7];   // ;

        # 1
        $data[] = array(
            $container,
            array('from' => $from, 'to' => $to),
            new TokenContainer("<?php /*\$blub = \$bla;*/ ?>"),
            false
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers \PHP\Manipulator\ContainerManipulator\CreateMultilineCommentFromTokenToToken::manipulate
     * @covers \PHP\Manipulator\ContainerManipulator\CreateMultilineCommentFromTokenToToken::<protected>
     */
    public function testManipulate($container, $params, $expectedContainer, $strict)
    {
        $manipulator = new CreateMultilineCommentFromTokenToToken();
        $manipulator->manipulate($container, $params);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }

    /**
     * @covers \PHP\Manipulator\ContainerManipulator\CreateMultilineCommentFromTokenToToken::manipulate
     * @covers \Exception
     */
    public function testNonArrayAsParamsThrowsException()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $params = null;
        $manipulator = new CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('invalid input $params should be an array', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\ContainerManipulator\CreateMultilineCommentFromTokenToToken::manipulate
     * @covers \Exception
     */
    public function testMissingFromThrowsException()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $params = array('to' => $container[5]);
        $manipulator = new CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("key 'from' not found in \$params", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\ContainerManipulator\CreateMultilineCommentFromTokenToToken::manipulate
     * @covers \Exception
     */
    public function testMissingToThrowsException()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $params = array('from' => $container[1]);
        $manipulator = new CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("key 'to' not found in \$params", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\ContainerManipulator\CreateMultilineCommentFromTokenToToken::manipulate
     * @covers \Exception
     */
    public function testWrongDatatypeForFromThrowsException()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $params = array('to' => $container[5], 'from' => 'foo');
        $manipulator = new CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("key 'from' is not instance of PHP\Manipulator\Token", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\ContainerManipulator\CreateMultilineCommentFromTokenToToken::manipulate
     * @covers \Exception
     */
    public function testWrongDatatypeForToThrowsException()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $params = array('to' => 'foo', 'from' => $container[1]);
        $manipulator = new CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("key 'to' is not instance of PHP\Manipulator\Token", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\ContainerManipulator\CreateMultilineCommentFromTokenToToken::manipulate
     * @covers \Exception
     */
    public function testFromTokenisBehindToTokenThrowsException()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $params = array('to' => $container[1], 'from' => $container[5]);
        $manipulator = new CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("startOffset is behind endOffset", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\ContainerManipulator\CreateMultilineCommentFromTokenToToken::manipulate
     * @covers \Exception
     */
    public function testToIsNotContainedInTheContainerThrowsException()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $params = array('to' => new Token('('), 'from' => $container[1]);
        $manipulator = new CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("element 'to' not found in \$container", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\ContainerManipulator\CreateMultilineCommentFromTokenToToken::manipulate
     * @covers \Exception
     */
    public function testFromIsNotContainedInTheContainerThrowsException()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $params = array('from' => new Token('('), 'to' => $container[1]);
        $manipulator = new CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->manipulate($container, $params);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("element 'from' not found in \$container", $e->getMessage(), 'Wrong exception message');
        }
    }
}