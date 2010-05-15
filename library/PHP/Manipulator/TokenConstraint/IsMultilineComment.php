<?php

namespace PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

class IsMultilineComment
extends TokenConstraint
{

    /**
     * Evaluate if the token is a multiline comment
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $param = null)
    {
        if ($token->getType() === T_COMMENT) {
            $value = $token->getValue();
            if (strlen($value) > 2 && substr($value, 0, 2) === '/*') {
                return true;
            }
        } else if ($token->getType() === T_DOC_COMMENT) {
            return true;
        }
        return false;
    }
}