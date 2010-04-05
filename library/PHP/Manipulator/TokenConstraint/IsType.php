<?php

class PHP_Manipulator_TokenConstraint_IsType
extends PHP_Manipulator_TokenConstraint_Abstract
{

    /**
     * Evaluate if the token is of a Type
     *
     * @param PHP_Manipulator_Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(PHP_Manipulator_Token $token, $param = null)
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
