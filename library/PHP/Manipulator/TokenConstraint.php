<?php

namespace PHP\Manipulator;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Token;

abstract class TokenConstraint
extends AHelper
{

    /**
     * Evaluates a constraint for a token $token.
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     * @return bool
     */
    abstract public function evaluate(Token $token, $params = null);

}