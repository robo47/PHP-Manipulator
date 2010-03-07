<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';
require_once 'PHP/Formatter/TokenManipulator/RemoveBeginNewline.php';

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
            true,
            true
        );

        # 1
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r\r")),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r")),
            true,
            true
        );

        # 2
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r\n\r\n")),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r\n")),
            true,
            true
        );

        # 3
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n")),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "")),
            true,
            true
        );

        # 4
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r\n")),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "")),
            true,
            true
        );

        # 5
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r")),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "")),
            true,
            true
        );

        # 6
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r\n\r")),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r")),
            true,
            true
        );

        #7
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n\r\n\r")),
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\r\n\r")),
            true,
            true
        );

        #8
        $data[] = array(
            PHP_Formatter_Token::factory("\n"),
            PHP_Formatter_Token::factory(""),
            true,
            true
        );

        #9
        $data[] = array(
            PHP_Formatter_Token::factory("\n\n"),
            PHP_Formatter_Token::factory("\n"),
            true,
            true
        );

        #10
        $data[] = array(
            PHP_Formatter_Token::factory("\r\r"),
            PHP_Formatter_Token::factory("\r"),
            true,
            true
        );

        #11
        $data[] = array(
            PHP_Formatter_Token::factory("\r\n\r\n"),
            PHP_Formatter_Token::factory("\r\n"),
            true,
            true
        );

        #12
        $data[] = array(
            PHP_Formatter_Token::factory(" "),
            PHP_Formatter_Token::factory(" "),
            false,
            true
        );

        #13
        $data[] = array(
            PHP_Formatter_Token::factory(" \n"),
            PHP_Formatter_Token::factory(" \n"),
            false,
            true
        );

        #14
        $data[] = array(
            PHP_Formatter_Token::factory(" \r"),
            PHP_Formatter_Token::factory(" \r"),
            false,
            true
        );

        #15
        $data[] = array(
            PHP_Formatter_Token::factory(" \r\n"),
            PHP_Formatter_Token::factory(" \r\n"),
            false,
            true
        );

        #16
        $data[] = array(
            PHP_Formatter_Token::factory(" \n\r"),
            PHP_Formatter_Token::factory(" \n\r"),
            false,
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers PHP_Formatter_TokenManipulator_RemoveBeginNewline::manipulate
     */
    public function testManipulate($token, $newToken, $changed, $strict)
    {
        $manipulator = new PHP_Formatter_TokenManipulator_RemoveBeginNewline();
        
        $this->assertSame($changed, $manipulator->manipulate($token), 'Wrong return value');
        $this->assertTokenMatch($token, $newToken, $strict);
    }
}