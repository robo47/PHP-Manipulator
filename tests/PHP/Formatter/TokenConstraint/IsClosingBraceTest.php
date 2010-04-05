<?php

/**
 * @group TokenConstraint_IsClosingBrace
 */
class PHP_Formatter_TokenConstraint_IsClosingBraceTest extends TestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            PHP_Formatter_Token::factory(array(null, '(')),
            false
        );

        #1
        $data[] = array(
            PHP_Formatter_Token::factory(array(null, ')')),
            true
        );

        #2
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, '(')),
            false
        );

        #3
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, ')')),
            false
        );


        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP_Formatter_TokenConstraint_IsClosingBrace::evaluate
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new PHP_Formatter_TokenConstraint_IsClosingBrace();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}