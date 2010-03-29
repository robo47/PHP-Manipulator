<?php

class PHP_Formatter_TokenManipulator_RemoveBeginNewlineTest extends PHPFormatterTestCase
{
    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = array();

        # 0
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n\n")),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n")),
            true
        );

        # 1
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r\r")),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r")),
            true
        );

        # 2
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r\n\r\n")),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r\n")),
            true
        );

        # 3
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n")),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "")),
            true
        );

        # 4
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r\n")),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "")),
            true
        );

        # 5
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r")),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "")),
            true
        );

        # 6
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r\n\r")),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r")),
            true
        );

        #7
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n\r\n\r")),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r\n\r")),
            true
        );

        #8
        $data[] = array(
            PHP_Formatter_Token::factory("\n"),
            PHP_Formatter_Token::factory(""),
            true
        );

        #9
        $data[] = array(
            PHP_Formatter_Token::factory("\n\n"),
            PHP_Formatter_Token::factory("\n"),
            true
        );

        #10
        $data[] = array(
            PHP_Formatter_Token::factory("\r\r"),
            PHP_Formatter_Token::factory("\r"),
            true
        );

        #11
        $data[] = array(
            PHP_Formatter_Token::factory("\r\n\r\n"),
            PHP_Formatter_Token::factory("\r\n"),
            true
        );

        #12
        $data[] = array(
            PHP_Formatter_Token::factory(" "),
            PHP_Formatter_Token::factory(" "),
            true
        );

        #13
        $data[] = array(
            PHP_Formatter_Token::factory(" \n"),
            PHP_Formatter_Token::factory(" \n"),
            true
        );

        #14
        $data[] = array(
            PHP_Formatter_Token::factory(" \r"),
            PHP_Formatter_Token::factory(" \r"),
            true
        );

        #15
        $data[] = array(
            PHP_Formatter_Token::factory(" \r\n"),
            PHP_Formatter_Token::factory(" \r\n"),
            true
        );

        #16
        $data[] = array(
            PHP_Formatter_Token::factory(" \n\r"),
            PHP_Formatter_Token::factory(" \n\r"),
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers PHP_Formatter_TokenManipulator_RemoveBeginNewline::manipulate
     */
    public function testManipulate($token, $newToken, $strict)
    {
        $manipulator = new PHP_Formatter_TokenManipulator_RemoveBeginNewline();
        $manipulator->manipulate($token);
        $this->assertTokenMatch($token, $newToken, $strict);
    }
}