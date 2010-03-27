<?php

require_once 'PHP/Formatter/TokenConstraint/Abstract.php';

class PHP_Formatter_TokenConstraint_IsType
extends PHP_Formatter_TokenConstraint_Abstract
{

    /**
     * Evaluate if the token is of a Type
     *
     * @param PHP_Formatter_Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(PHP_Formatter_Token $token, $param = null)
    {
        $isType = false;
        if (is_array($param)) {
            foreach ($param as $tokenType) {
                if ($token->getType() === $tokenType) {
                    $isType = true;
                    break;
                }
            }
        } else {
            if ($token->getType() === $param) {
                $isType = true;
            }
        }
        return $isType;
    }
}
