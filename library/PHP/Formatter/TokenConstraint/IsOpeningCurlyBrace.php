<?php

require_once 'PHP/Formatter/TokenConstraint/Abstract.php';

class PHP_Formatter_TokenConstraint_IsOpeningCurlyBrace
extends PHP_Formatter_TokenConstraint_Abstract
{

    /**
     * Evaluate if the token is an opening curly brace {
     *
     * @param PHP_Formatter_Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(PHP_Formatter_Token $token, $param = null)
    {
        if (null === $token->getType() && $token->getValue() === '{') {
            return true;
        }
        return false;
    }
}
