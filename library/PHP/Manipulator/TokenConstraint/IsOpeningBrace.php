<?php

namespace PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

class IsOpeningBrace
extends TokenConstraint
{

    /**
     * Evaluate if the token is an opening curly brace {
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $param = null)
    {
        if (null === $token->getType() && $token->getValue() === '(') {
            return true;
        }
        return false;
    }
}