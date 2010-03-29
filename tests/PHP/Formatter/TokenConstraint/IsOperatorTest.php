<?php

class PHP_Formatter_TokenConstraint_IsOperatorTest extends PHPFormatterTestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        $tokens = array(
            // assignment operators
            T_AND_EQUAL, // &=
            T_CONCAT_EQUAL, // .=
            T_DIV_EQUAL, // /=
            T_MINUS_EQUAL, // -=
            T_MOD_EQUAL, // &=
            T_MUL_EQUAL, // *=
            T_OR_EQUAL, // |=
            T_PLUS_EQUAL, // +=
            T_SR_EQUAL, // >>=
            T_SL_EQUAL, // <<=
            T_XOR_EQUAL, // ^=

            // logical operators
            T_LOGICAL_AND, // and
            T_LOGICAL_OR, // or
            T_LOGICAL_XOR, // xor
            T_BOOLEAN_AND, // &&
            T_BOOLEAN_OR, // ||

            // bitwise operators
            T_SL, // <<
            T_SR, // >>

            // incrementing/decrementing operators
            T_DEC, // --
            T_INC, // ++

            // comparision operators
            T_IS_EQUAL, // ==
            T_IS_GREATER_OR_EQUAL, // >=
            T_IS_IDENTICAL, // ===
            T_IS_NOT_EQUAL, // != or <>
            T_IS_NOT_IDENTICAL, // !==
            T_IS_SMALLER_OR_EQUAL, // <=

            // type-operators
            T_INSTANCEOF, // instanceof
        );

        foreach ($tokens as $type) {
            $data[] = array(
                PHP_Formatter_Token::factory(array($type, '==')),
                null,
                true
            );
        }

        $data[] = array(
            PHP_Formatter_Token::factory(array(null, '=')),
            null,
            true
        );

        $data[] = array(
            PHP_Formatter_Token::factory(array(T_COMMENT, '=')),
            null,
            false
        );

        $data[] = array(
            PHP_Formatter_Token::factory(array(null, '~')),
            null,
            false
        );


        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP_Formatter_TokenConstraint_IsOperator::evaluate
     */
    public function testEvaluate($token, $param, $result)
    {
        $constraint = new PHP_Formatter_TokenConstraint_IsOperator();
        $this->assertSame($result, $constraint->evaluate($token, $param), 'Wrong result');
    }
}