<?php

/**
 * @group TokenManipulator_RemoveCommentIndention
 */
class PHP_Formatter_TokenManipulator_RemoveCommentIndentionTest extends TestCase
{

    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_DOC_COMMENT, "/**\n* @return array\n     */\n")),
            PHP_Formatter_Token::factory(array(T_DOC_COMMENT, "/**\n* @return array\n*/\n")),
            true
        );

        #1
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, "/*\n* @return array\n     */\n")),
            PHP_Formatter_Token::factory(array(T_COMMENT, "/*\n* @return array\n*/\n")),
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers PHP_Formatter_TokenManipulator_RemoveCommentIndention::manipulate
     */
    public function testManipulate($token, $expectedToken, $strict)
    {
        $manipulator = new PHP_Formatter_TokenManipulator_RemoveCommentIndention();
        $manipulator->manipulate($token);
        $this->assertTokenMatch($expectedToken, $token, $strict);
    }
}