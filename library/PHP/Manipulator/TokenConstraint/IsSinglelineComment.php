<?php

namespace PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

class IsSinglelineComment
extends TokenConstraint
{

    /**
     * Evaluate if the token is a Singleline comment
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $param = null)
    {
        $isSinglelineComment = false;
        if ($token->getType() === T_COMMENT) {
            $value = $token->getValue();
            if (strlen($value) >= 1 && substr($value, 0, 1) == '#') {
                $isSinglelineComment = true;
            } else if (strlen($value) >= 2 && substr($value, 0, 2) == '//') {
                $isSinglelineComment = true;
            }
        }
        return $isSinglelineComment;
    }
}