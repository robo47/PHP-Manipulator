<?php

namespace PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

class IsType
extends TokenConstraint
{

    /**
     * Evaluate if the token is of a Type
     *
     * @param PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $param = null)
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
