<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenManipulator\RemoveBeginNewline;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenManipulator\RemoveBeginNewline
 */
class RemoveBeginNewlineTest extends TestCase
{
    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = [];

        # 0
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\n\n"]),
            Token::createFromMixed([T_WHITESPACE, "\n"]),
        ];

        # 1
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\r\r"]),
            Token::createFromMixed([T_WHITESPACE, "\r"]),
        ];

        # 2
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\r\n\r\n"]),
            Token::createFromMixed([T_WHITESPACE, "\r\n"]),
        ];

        # 3
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\n"]),
            Token::createFromMixed([T_WHITESPACE, '']),
        ];

        # 4
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\r\n"]),
            Token::createFromMixed([T_WHITESPACE, '']),
        ];

        # 5
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\r"]),
            Token::createFromMixed([T_WHITESPACE, '']),
        ];

        # 6
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\r\n\r"]),
            Token::createFromMixed([T_WHITESPACE, "\r"]),
        ];

        #7
        $data[] = [
            Token::createFromMixed([T_WHITESPACE, "\n\r\n\r"]),
            Token::createFromMixed([T_WHITESPACE, "\r\n\r"]),
        ];

        #8
        $data[] = [
            Token::createFromMixed("\n"),
            Token::createFromMixed(''),
        ];

        #9
        $data[] = [
            Token::createFromMixed("\n\n"),
            Token::createFromMixed("\n"),
        ];

        #10
        $data[] = [
            Token::createFromMixed("\r\r"),
            Token::createFromMixed("\r"),
        ];

        #11
        $data[] = [
            Token::createFromMixed("\r\n\r\n"),
            Token::createFromMixed("\r\n"),
        ];

        #12
        $data[] = [
            Token::createFromMixed(' '),
            Token::createFromMixed(' '),
        ];

        #13
        $data[] = [
            Token::createFromMixed(" \n"),
            Token::createFromMixed(" \n"),
        ];

        #14
        $data[] = [
            Token::createFromMixed(" \r"),
            Token::createFromMixed(" \r"),
        ];

        #15
        $data[] = [
            Token::createFromMixed(" \r\n"),
            Token::createFromMixed(" \r\n"),
        ];

        #16
        $data[] = [
            Token::createFromMixed(" \n\r"),
            Token::createFromMixed(" \n\r"),
        ];

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     *
     * @param Token $actualToken
     * @param Token $expectedToken
     */
    public function testManipulate(Token $actualToken, Token $expectedToken)
    {
        $manipulator = new RemoveBeginNewline();
        $manipulator->manipulate($actualToken);
        $this->assertTokenMatch($expectedToken, $actualToken, true);
    }
}
