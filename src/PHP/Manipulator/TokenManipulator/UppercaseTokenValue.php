<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenManipulator;

class UppercaseTokenValue extends TokenManipulator
{
    public function manipulate(Token $token, $params = null)
    {
        $token->setValue(strtoupper($token->getValue()));
    }
}
