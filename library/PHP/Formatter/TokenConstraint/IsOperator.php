<?php

class PHP_Formatter_TokenConstraint_IsOperator
extends PHP_Formatter_TokenConstraint_Abstract
{
    protected $_operatorsWithToken = array(
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

    protected $_operatorsWithoutTokens = array(
        '='
    );

    /**
     * Evaluate if the token is an operator
     *
     * @param PHP_Formatter_Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(PHP_Formatter_Token $token, $param = null)
    {
        $operatorWithToken = $this->evaluateConstraint('IsType', $token, $this->_operatorsWithToken);

        $operatorWithoutToken = false;
        foreach($this->_operatorsWithoutTokens as $operator) {
            if (null === $token->getType() && $operator === $token->getValue()) {
                $operatorWithoutToken = true;
                break;
            }
        }

        return $operatorWithToken || $operatorWithoutToken;
    }
}
