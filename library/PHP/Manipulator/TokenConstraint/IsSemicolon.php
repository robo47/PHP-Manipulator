<?php

namespace PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

class IsSemicolon
extends TokenConstraint
{

    /**
     * Evaluate if the token
     *
     * @param PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $params = null)
    {
        if ($token->getType() === null && $token->getValue() === ';') {
            return true;
        }
        return false;
    }
}