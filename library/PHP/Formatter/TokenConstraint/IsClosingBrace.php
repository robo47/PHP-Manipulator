<?php

class PHP_Formatter_TokenConstraint_IsClosingBrace
extends PHP_Formatter_TokenConstraint_Abstract
{

    /**
     * Evaluate if the token is a closing curly brace }
     *
     * @param PHP_Formatter_Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(PHP_Formatter_Token $token, $param = null)
    {
        if (null === $token->getType() && $token->getValue() === ')') {
            return true;
        }
        return false;
    }
}
