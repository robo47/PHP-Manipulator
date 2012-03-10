<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator\RemoveLeadingAndTrailingEmptyLinesInPhpdoc;
use PHP\Manipulator\Token;

/**
 * @group TokenManipulator\RemoveLeadingAndTrailingEmptyLinesInPhpdoc
 */
class RemoveLeadingAndTrailingEmptyLinesInPhpdocTest
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
            Token::factory(array(T_DOC_COMMENT, "/**\n     *\n     * @var boolean\n     *\n     */")),
            Token::factory(array(T_DOC_COMMENT, "/**\n     * @var boolean\n     */")),
        );

        #1
        $data[] = array(
            Token::factory(array(T_DOC_COMMENT, "/**\n     *\n     *\n     * @var boolean\n     *\n     *\n     */")),
            Token::factory(array(T_DOC_COMMENT, "/**\n     * @var boolean\n     */")),
        );

        #2 Other linebreaks (\r\n)
        $data[] = array(
            Token::factory(array(T_DOC_COMMENT, "/**\r\n     *\r\n     *\r\n     * @var boolean\r\n     *\r\n     *\r\n     */")),
            Token::factory(array(T_DOC_COMMENT, "/**\r\n     * @var boolean\r\n     */")),
        );

        #3 Other linebreaks 2 (\r)
        $data[] = array(
            Token::factory(array(T_DOC_COMMENT, "/**\r     *\r     *\r     * @var boolean\r     *\r     *\r     */")),
            Token::factory(array(T_DOC_COMMENT, "/**\r     * @var boolean\r     */")),
        );

        #4 Don't kill lines ending with *
        $data[] = array(
            Token::factory(array(T_DOC_COMMENT, "/**\n      * @param array \$actions\n      * @return \\PHP\\Manipulator *Provides Fluent Interface*\n      */")),
            Token::factory(array(T_DOC_COMMENT, "/**\n      * @param array \$actions\n      * @return \\PHP\\Manipulator *Provides Fluent Interface*\n      */")),
        );

        #5 Empty lines between other lines don't get deleted
        $data[] = array(
            Token::factory(array(T_DOC_COMMENT, "/**\n     *\n      * @param array \$actions\n      *\n      * @return \\PHP\\Manipulator *Provides Fluent Interface*\n     *\n      */")),
            Token::factory(array(T_DOC_COMMENT, "/**\n      * @param array \$actions\n      *\n      * @return \\PHP\\Manipulator *Provides Fluent Interface*\n      */")),
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers \PHP\Manipulator\TokenManipulator\RemoveLeadingAndTrailingEmptyLinesInPhpdoc
     */
    public function testManipulate($actualToken, $expectedToken)
    {
        $manipulator = new RemoveLeadingAndTrailingEmptyLinesInPhpdoc();
        $manipulator->manipulate($actualToken);
        $this->assertTokenMatch($expectedToken, $actualToken);
    }
}
