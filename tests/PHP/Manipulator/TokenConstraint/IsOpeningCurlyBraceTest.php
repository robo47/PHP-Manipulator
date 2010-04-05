<?php

/**
 * @group TokenConstraint_IsOpeningCurlyBrace
 */
class PHP_Manipulator_TokenConstraint_IsOpeningCurlyBraceTest extends TestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            PHP_Manipulator_Token::factory(array(null, '{')),
            true
        );

        #1
        $data[] = array(
            PHP_Manipulator_Token::factory(array(null, '}')),
            false
        );

        #2
        $data[] = array(
            PHP_Manipulator_Token::factory(array(T_COMMENT, '{')),
            false
        );

        #3
        $data[] = array(
            PHP_Manipulator_Token::factory(array(T_COMMENT, '}')),
            false
        );


        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP_Manipulator_TokenConstraint_IsOpeningCurlyBrace::evaluate
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new PHP_Manipulator_TokenConstraint_IsOpeningCurlyBrace();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}