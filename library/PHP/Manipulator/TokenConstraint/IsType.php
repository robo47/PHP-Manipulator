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
     * @param \PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $param = null)
    {
        if (is_array($param)) {
            foreach ($param as $tokenType) {
                if ($token->getType() === $tokenType) {
                    return true;
                }
            }
        } else {
            if ($token->getType() === $param) {
                return true;
            }
        }
        return false;
    }
}
