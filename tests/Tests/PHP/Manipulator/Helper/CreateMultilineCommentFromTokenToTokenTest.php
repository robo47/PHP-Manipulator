<?php

namespace Tests\PHP\Manipulator\Helper;

use PHP\Manipulator\Helper\CreateMultilineCommentFromTokenToToken;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Helper
 * @group Helper\CreateMultilineCommentFromTokenToToken
 */
class CreateMultilineCommentFromTokenToTokenTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();

        # 0
        $data[] = array(
            $c = new TokenContainer("<?php \$blub = \$bla; ?>"),
            $c[1],
            $c[6],
            new TokenContainer("<?php /*\$blub = \$bla;*/ ?>"),
            false
        );

        # 1 Multiline-comment in code
        $data[] = array(
            $c = new TokenContainer("<?php \$blub =/* foo */ \$bla; ?>"),
            $c[1],
            $c[7],
            new TokenContainer("<?php /*\$blub = \$bla;*/ ?>"),
            false
        );

        # 2 Multilinecomment nested in normal comment
        $data[] = array(
            $c = new TokenContainer("<?php \$blub =///* */\n \$bla; ?>"),
            $c[1],
            $c[7],
            new TokenContainer("<?php /*\$blub =///* \n \$bla;*/ ?>"),
            false
        );

        return $data;
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param \PHP\Manipulator\Token $from
     * @param \PHP\Manipulator\Token $to
     * @param \PHP\Manipulator\TokenContainer $expectedContainer
     * @param boolean $strict
     *
     * @dataProvider manipulateProvider
     * @covers \PHP\Manipulator\Helper\CreateMultilineCommentFromTokenToToken::run
     * @covers \PHP\Manipulator\Helper\CreateMultilineCommentFromTokenToToken::<protected>
     */
    public function testManipulate($container, $from, $to, $expectedContainer, $strict)
    {
        $manipulator = new CreateMultilineCommentFromTokenToToken();
        $manipulator->run($container, $from, $to);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }

    /**
     * @covers \PHP\Manipulator\Helper\CreateMultilineCommentFromTokenToToken::run
     * @covers \Exception
     */
    public function testFromTokenisBehindToTokenThrowsException()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $manipulator = new CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->run($container, $container[5], $container[1]);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("startOffset is behind endOffset", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\Helper\CreateMultilineCommentFromTokenToToken::run
     * @covers \Exception
     */
    public function testToIsNotContainedInTheContainerThrowsException()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $manipulator = new CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->run($container, $container[1], new Token('('));
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("element 'to' not found in \$container", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\Helper\CreateMultilineCommentFromTokenToToken::run
     * @covers \Exception
     */
    public function testFromIsNotContainedInTheContainerThrowsException()
    {
        $container = new TokenContainer("<?php echo 'hellow world'; ?>");
        $manipulator = new CreateMultilineCommentFromTokenToToken();
        try {
            $manipulator->run($container, new Token('('), $container[1]);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("element 'from' not found in \$container", $e->getMessage(), 'Wrong exception message');
        }
    }
}
