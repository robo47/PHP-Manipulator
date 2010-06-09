<?php

namespace PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

class EndsWithNewline
extends TokenConstraint
{

    /**
     * Evaluate if the token is a multiline comment
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $params = null)
    {
        $endsWithNewline = false;
        $pattern = '~(\n|\r\n|\r)$~';
        if (preg_match($pattern, $token->getValue())) {
            $endsWithNewline = true;
        }
        return $endsWithNewline;
    }
}