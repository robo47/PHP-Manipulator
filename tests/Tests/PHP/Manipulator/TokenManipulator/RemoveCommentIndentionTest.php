<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator\RemoveCommentIndention;
use PHP\Manipulator\Token;

/**
 * @group TokenFinder
 * @group TokenFinder\RemoveCommentIndention
 */
class RemoveCommentIndentionTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(T_DOC_COMMENT, "/**\n* @return array\n     */\n")),
            Token::factory(array(T_DOC_COMMENT, "/**\n* @return array\n*/\n")),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_COMMENT, "/*\n* @return array\n     */\n")),
            Token::factory(array(T_COMMENT, "/*\n* @return array\n*/\n")),
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers \PHP\Manipulator\TokenManipulator\RemoveCommentIndention::manipulate
     */
    public function testManipulate($actualToken, $expectedToken, $strict)
    {
        $manipulator = new RemoveCommentIndention();
        $manipulator->manipulate($actualToken);
        $this->assertTokenMatch($expectedToken, $actualToken, $strict);
    }
}
