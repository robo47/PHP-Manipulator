<?php

namespace PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

class ContainsOnlyWhitespace
extends TokenConstraint
{

    /**
     * Evaluate if the token only contains whitespace
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $params = null)
    {
        if (true == preg_match('~^(\s)*$~', $token->getValue())) {
            return true;
        }
        return false;
    }
}