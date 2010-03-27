<?php

require_once 'PHP/Formatter/TokenConstraint/Interface.php';

class PHP_Formatter_TokenConstraint_IsClosingCurlyBrace
implements PHP_Formatter_TokenConstraint_Interface
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
        if(null === $token->getType() && $token->getValue() === '}') {
            return true;
        }
        return false;
    }
}
