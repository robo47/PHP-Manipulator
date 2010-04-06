<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator\RemoveBeginNewline;
use PHP\Manipulator\Token;

/**
 * @group TokenManipulator_RemoveBeginNewline
 */
class RemoveBeginNewlineTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = array();

        # 0
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\n\n")),
            Token::factory(array(T_WHITESPACE, "\n")),
            true
        );

        # 1
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\r\r")),
            Token::factory(array(T_WHITESPACE, "\r")),
            true
        );

        # 2
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\r\n\r\n")),
            Token::factory(array(T_WHITESPACE, "\r\n")),
            true
        );

        # 3
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\n")),
            Token::factory(array(T_WHITESPACE, "")),
            true
        );

        # 4
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\r\n")),
            Token::factory(array(T_WHITESPACE, "")),
            true
        );

        # 5
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\r")),
            Token::factory(array(T_WHITESPACE, "")),
            true
        );

        # 6
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\r\n\r")),
            Token::factory(array(T_WHITESPACE, "\r")),
            true
        );

        #7
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\n\r\n\r")),
            Token::factory(array(T_WHITESPACE, "\r\n\r")),
            true
        );

        #8
        $data[] = array(
            Token::factory("\n"),
            Token::factory(""),
            true
        );

        #9
        $data[] = array(
            Token::factory("\n\n"),
            Token::factory("\n"),
            true
        );

        #10
        $data[] = array(
            Token::factory("\r\r"),
            Token::factory("\r"),
            true
        );

        #11
        $data[] = array(
            Token::factory("\r\n\r\n"),
            Token::factory("\r\n"),
            true
        );

        #12
        $data[] = array(
            Token::factory(" "),
            Token::factory(" "),
            true
        );

        #13
        $data[] = array(
            Token::factory(" \n"),
            Token::factory(" \n"),
            true
        );

        #14
        $data[] = array(
            Token::factory(" \r"),
            Token::factory(" \r"),
            true
        );

        #15
        $data[] = array(
            Token::factory(" \r\n"),
            Token::factory(" \r\n"),
            true
        );

        #16
        $data[] = array(
            Token::factory(" \n\r"),
            Token::factory(" \n\r"),
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers PHP\Manipulator\TokenManipulator\RemoveBeginNewline::manipulate
     */
    public function testManipulate($token, $newToken, $strict)
    {
        $manipulator = new RemoveBeginNewline();
        $manipulator->manipulate($token);
        $this->assertTokenMatch($token, $newToken, $strict);
    }
}