<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenManipulator\RemoveLeadingAndTrailingEmptyLinesInPhpdoc;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenManipulator\RemoveLeadingAndTrailingEmptyLinesInPhpdoc
 */
class RemoveLeadingAndTrailingEmptyLinesInPhpdocTest extends TestCase
{
    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromMixed([T_DOC_COMMENT, "/**\n     *\n     * @var bool\n     *\n     */"]),
            Token::createFromMixed([T_DOC_COMMENT, "/**\n     * @var bool\n     */"]),
        ];

        #1
        $data[] = [
            Token::createFromMixed([T_DOC_COMMENT, "/**\n     *\n     *\n     * @var bool\n     *\n     *\n     */"]),
            Token::createFromMixed([T_DOC_COMMENT, "/**\n     * @var bool\n     */"]),
        ];

        #2 Other linebreaks (\r\n)
        $data[] = [
            Token::createFromMixed([
                T_DOC_COMMENT,
                "/**\r\n     *\r\n     *\r\n     * @var bool\r\n     *\r\n     *\r\n     */",
            ]),
            Token::createFromMixed([T_DOC_COMMENT, "/**\r\n     * @var bool\r\n     */"]),
        ];

        #3 Other linebreaks 2 (\r)
        $data[] = [
            Token::createFromMixed([T_DOC_COMMENT, "/**\r     *\r     *\r     * @var bool\r     *\r     *\r     */"]),
            Token::createFromMixed([T_DOC_COMMENT, "/**\r     * @var bool\r     */"]),
        ];

        #4 Don't kill lines ending with *
        $data[] = [
            Token::createFromMixed([
                T_DOC_COMMENT,
                "/**\n      * @param array \$var\n      * @return int\n      */",
            ]),
            Token::createFromMixed([
                T_DOC_COMMENT,
                "/**\n      * @param array \$var\n      * @return int\n      */",
            ]),
        ];

        #5 Empty lines between other lines don't get deleted
        $data[] = [
            Token::createFromMixed([
                T_DOC_COMMENT,
                "/**\n     *\n      * @param array \$var\n      *\n      * @return int\n     *\n      */",
            ]),
            Token::createFromMixed([
                T_DOC_COMMENT,
                "/**\n      * @param array \$var\n      *\n      * @return int\n      */",
            ]),
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
        $manipulator = new RemoveLeadingAndTrailingEmptyLinesInPhpdoc();
        $manipulator->manipulate($actualToken);
        $this->assertTokenMatch($expectedToken, $actualToken);
    }
}
