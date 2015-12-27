<?php

namespace PHP\Manipulator;

abstract class TokenManipulator extends AHelper
{
    /**
     * Manipulates a Token
     *
     * @param Token $token
     * @param mixed $params
     */
    abstract public function manipulate(Token $token, $params = null);
}
