<?php

namespace PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

class ContainsNewline
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
        return (bool)preg_match("~(\r|\n)~", $token->getValue());
    }
}