<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator;
use PHP\Manipulator\Token;

class LowercaseTokenValue
extends TokenManipulator
{

    /**
     * Lowercase for tokens value
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     */
    public function manipulate(Token $token, $params = null)
    {
        $token->setValue(strtolower($token->getValue()));
    }
}
