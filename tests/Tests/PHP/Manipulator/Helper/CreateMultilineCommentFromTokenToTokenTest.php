<?php

namespace Tests\PHP\Manipulator\Helper;

use PHP\Manipulator\Exception\HelperException;
use PHP\Manipulator\Helper\CreateMultilineCommentFromTokenToToken;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Helper\CreateMultilineCommentFromTokenToToken
 */
class CreateMultilineCommentFromTokenToTokenTest extends TestCase
{
    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = [];

        $data['Multiline-comment'] = [
            $c = TokenContainer::factory('<?php $blub = $bla; ?>'),
            $c[1],
            $c[6],
            TokenContainer::factory('<?php /*$blub = $bla;*/ ?>'),
        ];

        $data['Multiline-comment in code'] = [
            $c = TokenContainer::factory('<?php $blub =/* foo */ $bla; ?>'),
            $c[1],
            $c[7],
            TokenContainer::factory('<?php /*$blub = $bla;*/ ?>'),
        ];

        $data['Multiline-comment nested in normal comment'] = [
            $c = TokenContainer::factory("<?php \$blub =///* */\n \$bla; ?>"),
            $c[1],
            $c[7],
            TokenContainer::factory("<?php /*\$blub =///* \n \$bla;*/ ?>"),
        ];

        return $data;
    }

    /**
     * @param TokenContainer $container
     * @param Token          $from
     * @param Token          $to
     * @param TokenContainer $expectedContainer
     *
     * @dataProvider manipulateProvider
     */
    public function testManipulate($container, $from, $to, $expectedContainer)
    {
        $manipulator = new CreateMultilineCommentFromTokenToToken();
        $manipulator->run($container, $from, $to);
        $this->assertTokenContainerMatch($expectedContainer, $container);
    }

    public function testFromTokenisBehindToTokenThrowsException()
    {
        $container   = TokenContainer::factory("<?php echo 'hellow world'; ?>");
        $manipulator = new CreateMultilineCommentFromTokenToToken();

        $this->setExpectedException(HelperException::class, '', HelperException::START_OFFSET_BEHIND_END_OFFSET);
        $manipulator->run($container, $container[5], $container[1]);
    }

    public function testToIsNotContainedInTheContainerThrowsException()
    {
        $container   = TokenContainer::factory("<?php echo 'hellow world'; ?>");
        $manipulator = new CreateMultilineCommentFromTokenToToken();

        $this->setExpectedException(HelperException::class, '', HelperException::TO_NOT_FOUND_IN_CONTAINER);
        $manipulator->run($container, $container[1], Token::createFromValue('('));
    }

    public function testFromIsNotContainedInTheContainerThrowsException()
    {
        $container   = TokenContainer::factory("<?php echo 'hellow world'; ?>");
        $manipulator = new CreateMultilineCommentFromTokenToToken();
        $this->setExpectedException(HelperException::class, '', HelperException::FROM_NOT_FOUND_IN_CONTAINER);
        $manipulator->run($container, Token::createFromValue('('), $container[1]);
    }
}
