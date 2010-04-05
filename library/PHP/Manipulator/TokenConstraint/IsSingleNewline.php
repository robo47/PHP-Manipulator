<?php

namespace PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;


class IsSingleNewline
extends TokenConstraint
{

    /**
     * Evaluate if the token is a multiline comment
     *
     * @param PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $param = null)
    {
        $isNewline = false;
        $value = $token->getValue();
        if ($value === "\n" ||
            $value === "\r\n" ||
            $value === "\r") {
            $isNewline = true;
        }
        return $isNewline;
    }
}
