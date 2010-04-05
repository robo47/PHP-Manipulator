<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator;
use PHP\Manipulator\Token;

class UppercaseTokenValue
extends TokenManipulator
{

    /**
     * Uppercase for tokens value
     *
     * @param PHP\Manipulator\Token $token
     * @param mixed $params
     * @return boolean
     */
    public function manipulate(Token $token, $params = null)
    {
        $token->setValue(strtoupper($token->getValue()));
    }
}