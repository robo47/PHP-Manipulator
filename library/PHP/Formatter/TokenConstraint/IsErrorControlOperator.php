<?php

class PHP_Formatter_TokenConstraint_IsErrorControlOperator
extends PHP_Formatter_TokenConstraint_Abstract
{

    /**
     * Evaluate if the token is
     *
     * @param PHP_Formatter_Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(PHP_Formatter_Token $token, $params = null)
    {
        $result = false;
        if (null === $token->getType() && '@' === $token->getValue()) {
            $result = true;
        }
        return $result;
    }
}
