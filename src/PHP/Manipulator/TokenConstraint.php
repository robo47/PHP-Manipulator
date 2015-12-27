<?php

namespace PHP\Manipulator;

abstract class TokenConstraint extends AHelper
{
    /**
     * Evaluates a constraint for a token $token.
     *
     * @param Token $token
     * @param mixed $params
     *
     * @return bool
     */
    abstract public function evaluate(Token $token, $params = null);
}
