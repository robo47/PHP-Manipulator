<?php

namespace PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

class IsErrorControlOperator
extends TokenConstraint
{

    /**
     * Evaluate if the token is
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $params = null)
    {
        if (null === $token->getType() && '@' === $token->getValue()) {
            return true;
        }
        return false;
    }
}