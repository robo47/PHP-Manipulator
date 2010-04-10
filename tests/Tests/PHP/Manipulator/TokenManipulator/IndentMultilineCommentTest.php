<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator\IndentMultilineComment;
use PHP\Manipulator\Token;

/**
 * @group TokenManipulator_IndentMultilineComment
 */
class IndentMultilineCommentTest
extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(T_DOC_COMMENT, "/**\n* @return array\n*/\n")),
            Token::factory(array(T_DOC_COMMENT, "/**\n     * @return array\n     */\n")),
            '    ',
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_COMMENT, "/*\n* @return array\n*/\n")),
            Token::factory(array(T_COMMENT, "/*\n     * @return array\n     */\n")),
            '    ',
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers \PHP\Manipulator\TokenManipulator\IndentMultilineComment::manipulate
     */
    public function testManipulate($actualToken, $expectedToken, $indention, $strict)
    {
        $manipulator = new IndentMultilineComment();
        $manipulator->manipulate($actualToken, $indention);
        $this->assertTokenMatch($expectedToken, $actualToken, $strict);
    }
}