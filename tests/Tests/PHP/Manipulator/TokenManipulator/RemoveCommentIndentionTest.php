<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenManipulator\RemoveCommentIndention;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenManipulator\RemoveCommentIndention
 */
class RemoveCommentIndentionTest extends TestCase
{
    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([T_DOC_COMMENT, "/**\n* @return array\n     */\n"]),
            Token::createFromMixed([T_DOC_COMMENT, "/**\n* @return array\n*/\n"]),
        ];

        #1
        $data[] = [
            Token::createFromMixed([T_COMMENT, "/*\n* @return array\n     */\n"]),
            Token::createFromMixed([T_COMMENT, "/*\n* @return array\n*/\n"]),
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
        $manipulator = new RemoveCommentIndention();
        $manipulator->manipulate($actualToken);
        $this->assertTokenMatch($expectedToken, $actualToken, true);
    }
}
