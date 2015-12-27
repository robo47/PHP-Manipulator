<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenManipulator;

class RemoveBeginNewline extends TokenManipulator
{
    public function manipulate(Token $token, $params = null)
    {
        $value = $token->getValue();

        if (substr($value, 0, 2) === "\r\n") {
            $token->setValue(substr($value, 2));
        } elseif (substr($value, 0, 1) === "\n" || substr($value, 0, 1) === "\r") {
            $token->setValue(substr($value, 1));
        }
    }
}
