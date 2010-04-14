<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator\MultilineToSinglelineComment;
use PHP\Manipulator\Token;

/**
 * @group TokenFinder
 * @group TokenFinder\MultilineToSinglelineComment
 */
class MultilineToSinglelineCommentTest
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
            Token::factory(array(T_COMMENT, "/*Foo\n * Foo\n */")),
            Token::factory(array(T_COMMENT, "//Foo\n// Foo\n//\n")),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_COMMENT, "/*\n * \n */")),
            Token::factory(array(T_COMMENT, "//\n// \n//\n")),
            true
        );

        #3
        $data[] = array(
            Token::factory(array(T_DOC_COMMENT, "/**Foo\n * Foo\n */")),
            Token::factory(array(T_COMMENT, "//Foo\n// Foo\n//\n")),
            true
        );

        #4 Test with \r\n
        $data[] = array(
            Token::factory(array(T_DOC_COMMENT, "/**Foo\r\n * Foo\r\n */")),
            Token::factory(array(T_COMMENT, "//Foo\r\n// Foo\r\n//\r\n")),
            true
        );

        #5 Test with \r
        $data[] = array(
            Token::factory(array(T_DOC_COMMENT, "/**Foo\r * Foo\r */")),
            Token::factory(array(T_COMMENT, "//Foo\r// Foo\r//\r")),
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers \PHP\Manipulator\TokenManipulator\MultilineToSinglelineComment
     */
    public function testManipulate($actualToken, $expectedToken, $strict)
    {
        $manipulator = new MultilineToSinglelineComment();
        $manipulator->manipulate($actualToken);
        $this->assertTokenMatch($expectedToken, $actualToken, $strict);
    }
}