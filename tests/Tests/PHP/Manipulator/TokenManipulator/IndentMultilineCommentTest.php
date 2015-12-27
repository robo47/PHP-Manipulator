<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenManipulator\IndentMultilineComment;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenManipulator\IndentMultilineComment
 */
class IndentMultilineCommentTest extends TestCase
{
    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([T_DOC_COMMENT, "/**\n* @return array\n*/\n"]),
            Token::createFromMixed([T_DOC_COMMENT, "/**\n     * @return array\n     */\n"]),
            '    ',
        ];

        #1
        $data[] = [
            Token::createFromMixed([T_COMMENT, "/*\n* @return array\n*/\n"]),
            Token::createFromMixed([T_COMMENT, "/*\n     * @return array\n     */\n"]),
            '    ',
        ];

        #2
        $data[] = [
            Token::createFromMixed([T_COMMENT, "/*\r\n* @return array\r\n*/\r\n"]),
            Token::createFromMixed([T_COMMENT, "/*\r\n     * @return array\r\n     */\r\n"]),
            '    ',
        ];

        #3
        $data[] = [
            Token::createFromMixed([T_COMMENT, "/*\r* @return array\r*/\r"]),
            Token::createFromMixed([T_COMMENT, "/*\r     * @return array\r     */\r"]),
            '    ',
        ];

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     *
     * @param Token  $actualToken
     * @param Token  $expectedToken
     * @param string $indention
     */
    public function testManipulate(Token $actualToken, Token $expectedToken, $indention)
    {
        $manipulator = new IndentMultilineComment();
        $manipulator->manipulate($actualToken, $indention);
        $this->assertTokenMatch($expectedToken, $actualToken, true);
    }
}
