<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\Exception\TokenManipulatorException;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenManipulator\MultilineToSinglelineComment;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenManipulator\MultilineToSinglelineComment
 */
class MultilineToSinglelineCommentTest extends TestCase
{
    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([T_COMMENT, "/*Foo\n * Foo\n */"]),
            Token::createFromMixed([T_COMMENT, "//Foo\n// Foo\n//\n"]),
        ];

        #1
        $data[] = [
            Token::createFromMixed([T_COMMENT, "/*\n * \n */"]),
            Token::createFromMixed([T_COMMENT, "//\n// \n//\n"]),
        ];

        #3
        $data[] = [
            Token::createFromMixed([T_DOC_COMMENT, "/**Foo\n * Foo\n */"]),
            Token::createFromMixed([T_COMMENT, "//Foo\n// Foo\n//\n"]),
        ];

        #4 Test with \r\n
        $data[] = [
            Token::createFromMixed([T_DOC_COMMENT, "/**Foo\r\n * Foo\r\n */"]),
            Token::createFromMixed([T_COMMENT, "//Foo\r\n// Foo\r\n//\r\n"]),
        ];

        #5 Test with \r
        $data[] = [
            Token::createFromMixed([T_DOC_COMMENT, "/**Foo\r * Foo\r */"]),
            Token::createFromMixed([T_COMMENT, "//Foo\r// Foo\r//\r"]),
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
        $manipulator = new MultilineToSinglelineComment();
        $manipulator->manipulate($actualToken);
        $this->assertTokenMatch($expectedToken, $actualToken, true);
    }

    public function testManipulatorThrowsExceptionIfFirstTokenIsNotAMultilineComment()
    {
        $this->setExpectedException(
            TokenManipulatorException::class,
            '',
            TokenManipulatorException::TOKEN_IS_NO_MULTILINE_COMMENT
        );
        $finder = new MultilineToSinglelineComment();

        $finder->manipulate(Token::createFromValueAndType('//foo', T_COMMENT));
    }
}
