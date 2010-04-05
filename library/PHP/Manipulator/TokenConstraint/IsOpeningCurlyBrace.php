<?php

class PHP_Manipulator_TokenConstraint_IsOpeningCurlyBrace
extends PHP_Manipulator_TokenConstraint_Abstract
{

    /**
     * Evaluate if the token is an opening curly brace {
     *
     * @param PHP_Manipulator_Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(PHP_Manipulator_Token $token, $param = null)
    {
        if (null === $token->getType() && $token->getValue() === '{') {
            return true;
        }
        return false;
    }
}
