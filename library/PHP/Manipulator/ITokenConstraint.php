<?php

namespace PHP\Manipulator;

use PHP\Manipulator\Token;

interface ITokenConstraint
{

    /**
     * Evaluates a constraint for a token $token.
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     * @return bool
     */
    public function evaluate(Token $token, $params = null);

}